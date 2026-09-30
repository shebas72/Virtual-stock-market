<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantSubscriptionController extends Controller
{
    public function show(Request $request): View
    {
        $tenant = $request->user()->tenant;
        abort_unless($tenant, 404);

        return view('subscription.show', [
            'tenant' => $tenant->load('subscriptionPlan'),
            'plans' => SubscriptionPlan::query()->where('is_active', true)->orderBy('id')->get(),
            'userCount' => $tenant->users()->count(),
            'pendingInvitationsCount' => $tenant->invitations()
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->count(),
            'canManage' => $request->user()->isTenantOwner(),
        ]);
    }

    public function changePlan(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $tenant = $request->user()->tenant;
        abort_unless($tenant, 404);

        $plan = SubscriptionPlan::query()
            ->where('is_active', true)
            ->findOrFail($subscriptionPlan->id);

        $reservedSeats = $tenant->users()->count() + $tenant->invitations()
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->count();

        if ($reservedSeats > $plan->user_limit) {
            return back()->withErrors([
                'plan' => "This workspace has {$reservedSeats} users or pending invitations, more than the {$plan->user_limit} seats included in {$plan->name}.",
            ]);
        }

        $isTrialActive = $tenant->subscription_status === 'trialing'
            && $tenant->trial_ends_at?->isFuture();

        $updates = [
            'subscription_plan_id' => $plan->id,
            'subscription_plan' => $plan->name,
            'subscription_price' => $plan->effectivePrice(),
            'subscription_status' => $isTrialActive ? 'trialing' : 'active',
        ];

        if (! $isTrialActive) {
            $updates['subscription_ends_at'] = match ($plan->duration_unit) {
                'day' => now()->addDays($plan->duration_count),
                default => now()->addMonths($plan->duration_count),
            };
        }

        $tenant->update($updates);

        return redirect()->route('subscription.show')->with('success', "Subscription changed to {$plan->name}.");
    }
}