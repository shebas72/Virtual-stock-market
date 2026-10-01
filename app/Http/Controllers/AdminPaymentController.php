<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPayment;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function index(Request $request, PaymentGatewayService $gateways): View
    {
        $filters = $request->validate([
            'gateway' => ['nullable', Rule::in(array_keys(PaymentGatewayService::gateways()))],
            'status' => ['nullable', Rule::in([
                SubscriptionPayment::STATUS_PENDING,
                SubscriptionPayment::STATUS_COMPLETED,
                SubscriptionPayment::STATUS_CANCELLED,
                SubscriptionPayment::STATUS_FAILED,
            ])],
            'search' => ['nullable', 'string', 'max:120'],
        ]);

        $query = SubscriptionPayment::query()
            ->with(['user', 'tenant', 'subscriptionPlan'])
            ->latest();

        if (! empty($filters['gateway'])) {
            $query->where('gateway', $filters['gateway']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search) {
                $builder->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('tenant', fn ($tenant) => $tenant->where('name', 'like', "%{$search}%"));
            });
        }

        $payments = $query->paginate(20)->withQueryString();

        $metrics = [
            'collected' => (float) SubscriptionPayment::query()->completed()->sum('amount'),
            'paid' => SubscriptionPayment::query()->completed()->count(),
            'pending' => SubscriptionPayment::query()->where('status', SubscriptionPayment::STATUS_PENDING)->count(),
            'total' => SubscriptionPayment::query()->count(),
        ];

        return view('admin.payments.index', [
            'payments' => $payments,
            'metrics' => $metrics,
            'filters' => $filters,
            'currency' => $gateways->currency(),
            'paymentsEnabled' => $gateways->paymentsEnabled(),
            'gatewayToggles' => [
                PaymentGatewayService::STRIPE => $gateways->gatewayEnabled(PaymentGatewayService::STRIPE),
                PaymentGatewayService::PAYPAL => $gateways->gatewayEnabled(PaymentGatewayService::PAYPAL),
            ],
            'gatewayConfigured' => [
                PaymentGatewayService::STRIPE => $gateways->gatewayConfigured(PaymentGatewayService::STRIPE),
                PaymentGatewayService::PAYPAL => $gateways->gatewayConfigured(PaymentGatewayService::PAYPAL),
            ],
        ]);
    }
}
