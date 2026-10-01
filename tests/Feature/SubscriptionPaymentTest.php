<?php

use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use App\Models\Tenant;
use App\Models\User;

function createPaymentPlatformAdmin(): User
{
    $tenant = Tenant::create(['name' => 'Payment Platform', 'slug' => 'payment-platform', 'is_active' => true]);

    return User::factory()->create([
        'role' => 'admin',
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
        'is_active' => true,
    ]);
}

function createPaymentWorkspaceUser(): array
{
    $tenant = Tenant::create(['name' => 'Pay Workspace', 'slug' => 'pay-workspace', 'is_active' => true]);
    $owner = User::factory()->create([
        'name' => 'Paying Owner',
        'email' => 'paying.owner@example.com',
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
        'is_active' => true,
    ]);

    return [$tenant, $owner];
}

it('lets platform admins enable and disable payments and each gateway', function () {
    $admin = createPaymentPlatformAdmin();

    $this->actingAs($admin)->put(route('admin.subscription-plans.payments'), [
        'payments_enabled' => '1',
        'stripe_enabled' => '1',
    ])->assertRedirect();

    $this->assertDatabaseHas('subscription_settings', [
        'payments_enabled' => true,
        'stripe_enabled' => true,
        'paypal_enabled' => false,
    ]);

    $this->actingAs($admin)->put(route('admin.subscription-plans.payments'), [
        'payments_enabled' => '0',
    ])->assertRedirect();

    $this->assertDatabaseHas('subscription_settings', [
        'payments_enabled' => false,
        'stripe_enabled' => false,
        'paypal_enabled' => false,
    ]);
});

it('shows pay buttons for paid plans when payments are enabled', function () {
    [, $owner] = createPaymentWorkspaceUser();
    SubscriptionPlan::where('name', 'Professional')->update(['price' => '99.00', 'discounted_price' => '79.00']);

    $this->actingAs($owner)->get(route('subscription.show'))
        ->assertOk()
        ->assertSee('with Stripe')
        ->assertSee('with PayPal')
        ->assertSee('$79.00');
});

it('hides pay buttons and shows the offline message when payments are disabled', function () {
    [, $owner] = createPaymentWorkspaceUser();
    SubscriptionPlan::where('name', 'Professional')->update(['price' => '99.00']);
    SubscriptionSetting::current()->update(['payments_enabled' => false]);

    $this->actingAs($owner)->get(route('subscription.show'))
        ->assertOk()
        ->assertDontSee('with Stripe')
        ->assertSee('Online payments are currently disabled');
});

it('creates a pending payment and redirects to the sandbox checkout', function () {
    [$tenant, $owner] = createPaymentWorkspaceUser();
    $plan = SubscriptionPlan::where('name', 'Professional')->firstOrFail();
    $plan->update(['price' => '99.00', 'discounted_price' => '79.00']);

    $this->actingAs($owner)->post(route('subscription.payment.store'), [
        'subscription_plan_id' => $plan->id,
        'gateway' => 'stripe',
    ])->assertRedirect();

    $payment = SubscriptionPayment::firstOrFail();

    expect($payment->status)->toBe(SubscriptionPayment::STATUS_PENDING)
        ->and($payment->gateway)->toBe('stripe')
        ->and($payment->tenant_id)->toBe($tenant->id)
        ->and($payment->user_id)->toBe($owner->id)
        ->and((float) $payment->amount)->toBe(79.0);

    $this->actingAs($owner)->get(route('subscription.payment.show', $payment))
        ->assertOk()
        ->assertSee('Confirm payment');
});

it('completes a sandbox payment and activates the workspace subscription', function () {
    [$tenant, $owner] = createPaymentWorkspaceUser();
    $plan = SubscriptionPlan::where('name', 'Professional')->firstOrFail();
    $plan->update(['price' => '99.00', 'discounted_price' => '79.00']);

    $this->actingAs($owner)->post(route('subscription.payment.store'), [
        'subscription_plan_id' => $plan->id,
        'gateway' => 'paypal',
    ]);

    $payment = SubscriptionPayment::firstOrFail();

    $this->actingAs($owner)->post(route('subscription.payment.complete', $payment))
        ->assertRedirect(route('subscription.show'));

    expect($payment->fresh()->status)->toBe(SubscriptionPayment::STATUS_COMPLETED)
        ->and($payment->fresh()->paid_at)->not->toBeNull();

    $this->assertDatabaseHas('tenants', [
        'id' => $tenant->id,
        'subscription_plan_id' => $plan->id,
        'subscription_plan' => 'Professional',
        'subscription_status' => 'active',
        'subscription_price' => '79.00',
    ]);
});

it('rejects payments for a disabled gateway', function () {
    [, $owner] = createPaymentWorkspaceUser();
    SubscriptionSetting::current()->update(['stripe_enabled' => false]);
    $plan = SubscriptionPlan::where('name', 'Professional')->firstOrFail();
    $plan->update(['price' => '99.00']);

    $this->actingAs($owner)->post(route('subscription.payment.store'), [
        'subscription_plan_id' => $plan->id,
        'gateway' => 'stripe',
    ])->assertSessionHasErrors('payment');

    expect(SubscriptionPayment::count())->toBe(0);
});

it('shows which users paid on the admin payments report', function () {
    $admin = createPaymentPlatformAdmin();
    [, $owner] = createPaymentWorkspaceUser();
    $plan = SubscriptionPlan::where('name', 'Professional')->firstOrFail();

    SubscriptionPayment::create([
        'tenant_id' => $owner->tenant_id,
        'user_id' => $owner->id,
        'subscription_plan_id' => $plan->id,
        'gateway' => 'stripe',
        'amount' => '79.00',
        'currency' => 'USD',
        'status' => SubscriptionPayment::STATUS_COMPLETED,
        'paid_at' => now(),
    ]);

    $this->actingAs($admin)->get(route('admin.payments.index'))
        ->assertOk()
        ->assertSee('Subscription Payments')
        ->assertSee('Paying Owner')
        ->assertSee('paying.owner@example.com')
        ->assertSee('79.00')
        ->assertSee('Paid');
});
