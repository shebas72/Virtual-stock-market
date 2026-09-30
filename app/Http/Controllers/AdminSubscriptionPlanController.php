<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminSubscriptionPlanController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::query()->withCount('tenants')->orderBy('id')->get();
        $settings = SubscriptionSetting::query()->firstOrCreate([], ['trial_days' => 7]);

        return view('admin.subscription-plans.index', compact('plans', 'settings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedPlan($request);
        $makeDefault = $request->boolean('is_default') || ! SubscriptionPlan::where('is_default', true)->exists();

        DB::transaction(function () use ($validated, $makeDefault) {
            if ($makeDefault) {
                SubscriptionPlan::query()->update(['is_default' => false]);
            }

            SubscriptionPlan::create([
                ...$validated,
                'is_default' => $makeDefault,
            ]);
        });

        return back()->with('success', 'Subscription plan created.');
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $validated = $this->validatedPlan($request, $subscriptionPlan);
        $hasOtherDefault = SubscriptionPlan::query()
            ->where('is_default', true)
            ->whereKeyNot($subscriptionPlan->id)
            ->exists();
        $makeDefault = $request->boolean('is_default') || ! $hasOtherDefault;

        DB::transaction(function () use ($subscriptionPlan, $validated, $makeDefault) {
            if ($makeDefault) {
                SubscriptionPlan::query()->whereKeyNot($subscriptionPlan->id)->update(['is_default' => false]);
            }

            $subscriptionPlan->update([
                ...$validated,
                'is_default' => $makeDefault,
            ]);
        });

        return back()->with('success', 'Subscription plan updated.');
    }

    public function updateTrial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trial_days' => ['required', 'integer', 'min:0', 'max:365'],
        ]);

        SubscriptionSetting::query()->firstOrCreate([], ['trial_days' => 7])
            ->update(['trial_days' => $validated['trial_days']]);

        return back()->with('success', 'Default free-trial length updated.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        if ($subscriptionPlan->tenants()->exists()) {
            return back()->with('error', 'This plan is assigned to a tenant and cannot be deleted.');
        }

        if (SubscriptionPlan::count() === 1) {
            return back()->with('error', 'At least one subscription plan must remain.');
        }

        DB::transaction(function () use ($subscriptionPlan) {
            $wasDefault = $subscriptionPlan->is_default;
            $subscriptionPlan->delete();

            if ($wasDefault) {
                SubscriptionPlan::query()->orderBy('id')->first()?->update(['is_default' => true]);
            }
        });

        return back()->with('success', 'Subscription plan deleted.');
    }

    private function validatedPlan(Request $request, ?SubscriptionPlan $subscriptionPlan = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('subscription_plans', 'name')->ignore($subscriptionPlan?->id)],
            'user_limit' => ['required', 'integer', 'min:1', 'max:1000000'],
            'duration_count' => ['required', 'integer', 'min:1', 'max:365'],
            'duration_unit' => ['required', Rule::in(['day', 'month'])],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'discounted_price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99', 'lt:price'],
        ]);
    }
}