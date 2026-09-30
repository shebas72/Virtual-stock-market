<?php

use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use App\Models\Tenant;
use App\Models\User;

function createSubscriptionPlatformAdmin(): User
{
    $tenant = Tenant::create(['name' => 'Platform Workspace', 'slug' => 'platform-workspace', 'is_active' => true]);

    return User::factory()->create([
        'role' => 'admin',
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
        'is_active' => true,
    ]);
}

it('lets platform admins edit plan limits, terms, pricing, and the default trial length', function () {
    $admin = createSubscriptionPlatformAdmin();
    $plan = SubscriptionPlan::where('name', 'Professional')->firstOrFail();

    $this->actingAs($admin)->put(route('admin.subscription-plans.update', $plan), [
        'name' => 'Professional',
        'user_limit' => 40,
        'duration_count' => 3,
        'duration_unit' => 'month',
        'price' => '99.00',
        'discounted_price' => '79.00',
        'is_default' => '1',
    ])->assertRedirect();

    $this->assertDatabaseHas('subscription_plans', [
        'id' => $plan->id,
        'user_limit' => 40,
        'duration_count' => 3,
        'price' => '99.00',
        'discounted_price' => '79.00',
        'is_default' => true,
    ]);

    $this->actingAs($admin)->put(route('admin.subscription-plans.trial'), [
        'trial_days' => 14,
    ])->assertRedirect();

    expect(SubscriptionSetting::defaultTrialDays())->toBe(14);
});

it('shows subscription plan management from the admin panel', function () {
    $admin = createSubscriptionPlatformAdmin();

    $this->actingAs($admin)->get(route('admin.subscription-plans.index'))
        ->assertOk()
        ->assertSee('Subscription Plans')
        ->assertSee('Trial length (days)')
        ->assertSee('Users included')
        ->assertSee('Discounted price');
});

it('uses the configured default trial length for new tenants', function () {
    SubscriptionSetting::query()->firstOrCreate([], ['trial_days' => 7])->update(['trial_days' => 14]);

    $tenant = Tenant::create(['name' => 'Trial Workspace', 'slug' => 'trial-workspace']);

    expect($tenant->trial_ends_at->isSameDay(now()->addDays(14)))->toBeTrue()
        ->and($tenant->subscription_status)->toBe('trialing')
        ->and($tenant->subscription_plan_id)->not->toBeNull();
});

it('shows plan details and lets the workspace owner switch plans', function () {
    $tenant = Tenant::create(['name' => 'North Workspace', 'slug' => 'north-workspace', 'is_active' => true]);
    $owner = User::factory()->create([
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
        'is_active' => true,
    ]);
    $trialEndsAt = $tenant->trial_ends_at;
    $professional = SubscriptionPlan::where('name', 'Professional')->firstOrFail();
    $professional->update(['price' => '99.00', 'discounted_price' => '79.00']);

    $this->actingAs($owner)->get(route('subscription.show'))
        ->assertOk()
        ->assertSee('Available plans')
        ->assertSee('Users included')
        ->assertSee('Professional')
        ->assertSee('$99.00')
        ->assertSee('$79.00');

    $this->actingAs($owner)->post(route('subscription.change', $professional))
        ->assertRedirect(route('subscription.show'));

    $this->assertDatabaseHas('tenants', [
        'id' => $tenant->id,
        'subscription_plan_id' => $professional->id,
        'subscription_status' => 'trialing',
        'subscription_price' => '79.00',
    ]);
    expect($tenant->fresh()->trial_ends_at->equalTo($trialEndsAt))->toBeTrue();
});

it('blocks adding members and invitations when the plan has no seats left', function () {
    $tenant = Tenant::create(['name' => 'Small Workspace', 'slug' => 'small-workspace', 'is_active' => true]);
    $tenant->subscriptionPlan->update(['user_limit' => 1]);
    $owner = User::factory()->create([
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
        'is_active' => true,
    ]);

    $this->actingAs($owner)->post(route('team.members.store'), [
        'name' => 'Extra Member',
        'email' => 'extra@example.com',
        'password' => 'a-strong-password',
        'password_confirmation' => 'a-strong-password',
    ])->assertSessionHasErrors('email');

    $this->actingAs($owner)->post(route('team.invitations.store'), [
        'email' => 'invite@example.com',
    ])->assertSessionHasErrors('email');

    $this->assertDatabaseMissing('users', ['email' => 'extra@example.com']);
    $this->assertDatabaseMissing('tenant_invitations', ['email' => 'invite@example.com']);
});

it('rechecks the user limit when an invitation is accepted', function () {
    $tenant = Tenant::create(['name' => 'Invite Workspace', 'slug' => 'invite-workspace', 'is_active' => true]);
    $tenant->subscriptionPlan->update(['user_limit' => 2]);
    $owner = User::factory()->create([
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
        'is_active' => true,
    ]);

    $inviteResponse = $this->actingAs($owner)->post(route('team.invitations.store'), [
        'email' => 'late-member@example.com',
    ]);
    $url = $inviteResponse->getSession()->get('invitation_url');
    $token = basename(parse_url($url, PHP_URL_PATH));

    $tenant->subscriptionPlan->update(['user_limit' => 1]);

    $this->post(route('invitations.accept', $token), [
        'name' => 'Late Member',
        'password' => 'a-strong-password',
        'password_confirmation' => 'a-strong-password',
    ])->assertForbidden();

    $this->assertDatabaseMissing('users', ['email' => 'late-member@example.com']);
});