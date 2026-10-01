<?php

namespace App\Services;

use App\Models\SubscriptionPayment;
use App\Models\SubscriptionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaymentGatewayService
{
    public const STRIPE = 'stripe';

    public const PAYPAL = 'paypal';

    private ?SubscriptionSetting $settings = null;

    /**
     * Human readable labels for every supported gateway.
     *
     * @return array<string, string>
     */
    public static function gateways(): array
    {
        return [
            self::STRIPE => 'Stripe',
            self::PAYPAL => 'PayPal',
        ];
    }

    public function settings(): SubscriptionSetting
    {
        return $this->settings ??= SubscriptionSetting::current();
    }

    public function currency(): string
    {
        return strtoupper((string) config('services.payments.currency', 'USD'));
    }

    /**
     * The master switch for online payments.
     */
    public function paymentsEnabled(): bool
    {
        return (bool) $this->settings()->payments_enabled;
    }

    public function gatewayEnabled(string $gateway): bool
    {
        return match ($gateway) {
            self::STRIPE => (bool) $this->settings()->stripe_enabled,
            self::PAYPAL => (bool) $this->settings()->paypal_enabled,
            default => false,
        };
    }

    /**
     * Whether the gateway has credentials configured. When it does not the
     * service falls back to the built-in sandbox checkout so the flow still
     * works for demos and tests.
     */
    public function gatewayConfigured(string $gateway): bool
    {
        return match ($gateway) {
            self::STRIPE => filled(config('services.stripe.secret')),
            self::PAYPAL => filled(config('services.paypal.client_id')) && filled(config('services.paypal.secret')),
            default => false,
        };
    }

    /**
     * Gateways the workspace owner is allowed to pay with right now.
     *
     * @return list<string>
     */
    public function activeGateways(): array
    {
        if (! $this->paymentsEnabled()) {
            return [];
        }

        return array_values(array_filter(
            array_keys(self::gateways()),
            fn (string $gateway): bool => $this->gatewayEnabled($gateway),
        ));
    }

    /**
     * Start a checkout for the given payment and return the URL to redirect to.
     */
    public function launch(SubscriptionPayment $payment): string
    {
        return match ($payment->gateway) {
            self::STRIPE => $this->launchStripe($payment),
            self::PAYPAL => $this->launchPaypal($payment),
            default => throw new RuntimeException('Unsupported payment gateway: '.$payment->gateway),
        };
    }

    /**
     * Verify a returning gateway callback and return the gateway reference on
     * success, or null when the payment cannot be confirmed.
     */
    public function verify(SubscriptionPayment $payment, Request $request): ?string
    {
        return match ($payment->gateway) {
            self::STRIPE => $this->verifyStripe($payment, $request),
            self::PAYPAL => $this->verifyPaypal($payment, $request),
            default => null,
        };
    }

    /**
     * Mark a payment as completed and activate the workspace subscription.
     */
    public function complete(SubscriptionPayment $payment, ?string $reference = null): SubscriptionPayment
    {
        if ($payment->isCompleted()) {
            return $payment;
        }

        DB::transaction(function () use ($payment, $reference) {
            $payment->update([
                'status' => SubscriptionPayment::STATUS_COMPLETED,
                'reference' => $reference ?? $payment->reference,
                'paid_at' => now(),
            ]);

            $plan = $payment->subscriptionPlan;
            $tenant = $payment->tenant;

            if ($plan && $tenant) {
                $tenant->update([
                    'subscription_plan_id' => $plan->id,
                    'subscription_plan' => $plan->name,
                    'subscription_price' => $payment->amount,
                    'subscription_status' => 'active',
                    'subscription_ends_at' => match ($plan->duration_unit) {
                        'day' => now()->addDays($plan->duration_count),
                        default => now()->addMonths($plan->duration_count),
                    },
                ]);
            }
        });

        return $payment->refresh();
    }

    public function markCancelled(SubscriptionPayment $payment): SubscriptionPayment
    {
        if ($payment->isPending()) {
            $payment->update(['status' => SubscriptionPayment::STATUS_CANCELLED]);
        }

        return $payment->refresh();
    }

    public function markFailed(SubscriptionPayment $payment): SubscriptionPayment
    {
        if ($payment->isPending()) {
            $payment->update(['status' => SubscriptionPayment::STATUS_FAILED]);
        }

        return $payment->refresh();
    }

    private function launchStripe(SubscriptionPayment $payment): string
    {
        if (! $this->gatewayConfigured(self::STRIPE)) {
            return route('subscription.payment.show', $payment);
        }

        $response = Http::asForm()
            ->withToken((string) config('services.stripe.secret'))
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'mode' => 'payment',
                'success_url' => route('subscription.payment.callback', $payment).'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('subscription.payment.callback', $payment).'?cancelled=1',
                'client_reference_id' => (string) $payment->id,
                'line_items[0][quantity]' => 1,
                'line_items[0][price_data][currency]' => strtolower($payment->currency),
                'line_items[0][price_data][unit_amount]' => (int) round(((float) $payment->amount) * 100),
                'line_items[0][price_data][product_data][name]' => 'Subscription: '.($payment->subscriptionPlan?->name ?? 'Plan'),
            ]);

        if (! $response->successful() || ! is_string($response->json('url'))) {
            Log::error('Stripe checkout session could not be created.', [
                'payment_id' => $payment->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Unable to start the Stripe checkout. Please try again.');
        }

        $sessionId = $response->json('id');
        $payment->update([
            'reference' => $sessionId,
            'meta' => array_merge($payment->meta ?? [], ['stripe_session_id' => $sessionId]),
        ]);

        return $response->json('url');
    }

    private function launchPaypal(SubscriptionPayment $payment): string
    {
        if (! $this->gatewayConfigured(self::PAYPAL)) {
            return route('subscription.payment.show', $payment);
        }

        $base = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $tokenResponse = Http::asForm()
            ->withBasicAuth((string) config('services.paypal.client_id'), (string) config('services.paypal.secret'))
            ->post($base.'/v1/oauth2/token', ['grant_type' => 'client_credentials']);

        if (! $tokenResponse->successful() || ! is_string($tokenResponse->json('access_token'))) {
            Log::error('PayPal access token could not be retrieved.', [
                'payment_id' => $payment->id,
                'status' => $tokenResponse->status(),
            ]);

            throw new RuntimeException('Unable to reach PayPal. Please try again.');
        }

        $orderResponse = Http::withToken($tokenResponse->json('access_token'))
            ->post($base.'/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => (string) $payment->id,
                    'description' => 'Subscription: '.($payment->subscriptionPlan?->name ?? 'Plan'),
                    'amount' => [
                        'currency_code' => $payment->currency,
                        'value' => number_format((float) $payment->amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'brand_name' => (string) config('app.name'),
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('subscription.payment.callback', $payment),
                    'cancel_url' => route('subscription.payment.callback', $payment).'?cancelled=1',
                ],
            ]);

        if (! $orderResponse->successful()) {
            Log::error('PayPal order could not be created.', [
                'payment_id' => $payment->id,
                'status' => $orderResponse->status(),
                'body' => $orderResponse->body(),
            ]);

            throw new RuntimeException('Unable to start the PayPal checkout. Please try again.');
        }

        $orderId = $orderResponse->json('id');
        $approve = collect($orderResponse->json('links') ?? [])
            ->firstWhere('rel', 'approve')['href'] ?? null;

        if (! is_string($approve) || $approve === '') {
            throw new RuntimeException('PayPal did not return an approval link.');
        }

        $payment->update([
            'reference' => $orderId,
            'meta' => array_merge($payment->meta ?? [], ['paypal_order_id' => $orderId]),
        ]);

        return $approve;
    }

    private function verifyStripe(SubscriptionPayment $payment, Request $request): ?string
    {
        $sessionId = $request->query('session_id') ?: ($payment->meta['stripe_session_id'] ?? null);

        if (! is_string($sessionId) || $sessionId === '') {
            return null;
        }

        $response = Http::withToken((string) config('services.stripe.secret'))
            ->get('https://api.stripe.com/v1/checkout/sessions/'.$sessionId);

        if (! $response->successful() || $response->json('payment_status') !== 'paid') {
            return null;
        }

        return $sessionId;
    }

    private function verifyPaypal(SubscriptionPayment $payment, Request $request): ?string
    {
        $orderId = $request->query('token') ?: ($payment->meta['paypal_order_id'] ?? null);

        if (! is_string($orderId) || $orderId === '') {
            return null;
        }

        $base = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $tokenResponse = Http::asForm()
            ->withBasicAuth((string) config('services.paypal.client_id'), (string) config('services.paypal.secret'))
            ->post($base.'/v1/oauth2/token', ['grant_type' => 'client_credentials']);

        if (! $tokenResponse->successful()) {
            return null;
        }

        $capture = Http::withToken($tokenResponse->json('access_token'))
            ->withBody('{}', 'application/json')
            ->post($base.'/v2/checkout/orders/'.$orderId.'/capture');

        if (! $capture->successful() || $capture->json('status') !== 'COMPLETED') {
            return null;
        }

        return $orderId;
    }
}
