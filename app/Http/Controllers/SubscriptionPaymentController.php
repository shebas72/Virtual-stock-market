<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Services\PaymentGatewayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class SubscriptionPaymentController extends Controller
{
    public function store(Request $request, PaymentGatewayService $gateways): RedirectResponse
    {
        $tenant = $request->user()->tenant;
        abort_unless($tenant, 404);

        $validated = $request->validate([
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'gateway' => ['required', Rule::in(array_keys(PaymentGatewayService::gateways()))],
        ]);

        if (! $gateways->paymentsEnabled()) {
            return back()->withErrors(['payment' => 'Online payments are currently disabled. Please contact support.']);
        }

        if (! $gateways->gatewayEnabled($validated['gateway'])) {
            return back()->withErrors(['payment' => ucfirst($validated['gateway']).' payments are currently disabled.']);
        }

        $plan = SubscriptionPlan::query()
            ->where('is_active', true)
            ->findOrFail($validated['subscription_plan_id']);

        $reservedSeats = $tenant->users()->count() + $tenant->invitations()
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->count();

        if ($reservedSeats > $plan->user_limit) {
            return back()->withErrors([
                'plan' => "This workspace has {$reservedSeats} users or pending invitations, more than the {$plan->user_limit} seats included in {$plan->name}.",
            ]);
        }

        $payment = SubscriptionPayment::create([
            'tenant_id' => $tenant->id,
            'user_id' => $request->user()->id,
            'subscription_plan_id' => $plan->id,
            'gateway' => $validated['gateway'],
            'amount' => $plan->effectivePrice(),
            'currency' => $gateways->currency(),
            'status' => SubscriptionPayment::STATUS_PENDING,
        ]);

        try {
            $url = $gateways->launch($payment);
        } catch (RuntimeException $exception) {
            $payment->delete();

            return back()->withErrors(['payment' => $exception->getMessage()]);
        }

        return redirect()->to($url);
    }

    public function show(Request $request, SubscriptionPayment $payment): View
    {
        $this->authorizePayment($request, $payment);

        abort_unless($payment->isPending(), 404);

        return view('subscription.checkout', [
            'payment' => $payment->load(['subscriptionPlan', 'tenant']),
        ]);
    }

    public function complete(Request $request, SubscriptionPayment $payment, PaymentGatewayService $gateways): RedirectResponse
    {
        $this->authorizePayment($request, $payment);

        if (! $payment->isPending()) {
            return redirect()->route('subscription.show');
        }

        $gateways->complete($payment);

        return redirect()->route('subscription.show')
            ->with('success', "Payment received. {$payment->tenant?->name} is now subscribed to {$payment->subscriptionPlan?->name}.");
    }

    public function cancel(Request $request, SubscriptionPayment $payment, PaymentGatewayService $gateways): RedirectResponse
    {
        $this->authorizePayment($request, $payment);

        $gateways->markCancelled($payment);

        return redirect()->route('subscription.show')
            ->with('error', 'The payment was cancelled. No charge was made.');
    }

    public function callback(Request $request, SubscriptionPayment $payment, PaymentGatewayService $gateways): RedirectResponse
    {
        $this->authorizePayment($request, $payment);

        if ($request->boolean('cancelled') || ! $payment->isPending()) {
            $gateways->markCancelled($payment);

            return redirect()->route('subscription.show')
                ->with('error', 'The payment was cancelled. No charge was made.');
        }

        $reference = $gateways->verify($payment, $request);

        if ($reference === null) {
            $gateways->markFailed($payment);

            return redirect()->route('subscription.show')
                ->with('error', 'We could not confirm the payment with '.$payment->gatewayLabel().'. Please try again.');
        }

        $gateways->complete($payment, $reference);

        return redirect()->route('subscription.show')
            ->with('success', "Payment received. {$payment->tenant?->name} is now subscribed to {$payment->subscriptionPlan?->name}.");
    }

    private function authorizePayment(Request $request, SubscriptionPayment $payment): void
    {
        $tenant = $request->user()->tenant;

        abort_unless($tenant, 404);
        abort_unless($payment->tenant_id === $tenant->id, 403);
    }
}
