<?php

use App\Models\Portfolio;
use App\Models\Tenant;
use App\Models\User;

function createPlatformAdmin(): User
{
    $tenant = Tenant::create([
        'name' => 'Platform Workspace',
        'slug' => 'platform-workspace',
    ]);

    return User::factory()->create([
        'role' => 'admin',
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
    ]);
}

function createManagedTenant(string $name = 'Acme Workspace'): array
{
    $tenant = Tenant::create([
        'name' => $name,
        'slug' => strtolower(str_replace(' ', '-', $name)),
    ]);
    $owner = User::factory()->create([
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
    ]);
    $member = User::factory()->create([
        'tenant_id' => $tenant->id,
        'tenant_role' => 'member',
    ]);

    return [$tenant, $owner, $member];
}

it('shows all tenants with user-count metrics to the platform admin', function () {
    $admin = createPlatformAdmin();
    [$tenant] = createManagedTenant();

    $response = $this->actingAs($admin)->get(route('admin.tenants.index'));

    $response->assertOk();
    $response->assertSee($tenant->name);
    $response->assertSee('2', false);
});

it('allows the platform admin to edit a tenant name and slug', function () {
    $admin = createPlatformAdmin();
    [$tenant] = createManagedTenant();

    $response = $this->actingAs($admin)->put(route('admin.tenants.update', $tenant), [
        'name' => 'Renamed Workspace',
        'slug' => 'renamed-workspace',
        'subscription_plan' => 'starter',
        'subscription_price' => '0.00',
        'subscription_status' => 'trialing',
        'trial_ends_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
    ]);

    $response->assertRedirect(route('admin.tenants.index'));
    $this->assertDatabaseHas('tenants', [
        'id' => $tenant->id,
        'name' => 'Renamed Workspace',
        'slug' => 'renamed-workspace',
        'subscription_plan' => 'starter',
        'subscription_price' => '0.00',
    ]);
});

it('lets the platform admin assign a plan, custom price, and active subscription', function () {
    $admin = createPlatformAdmin();
    [$tenant] = createManagedTenant();

    $response = $this->actingAs($admin)->put(route('admin.tenants.update', $tenant), [
        'name' => $tenant->name,
        'slug' => $tenant->slug,
        'subscription_plan' => 'professional',
        'subscription_price' => '49.95',
        'subscription_status' => 'active',
        'subscription_ends_at' => now()->addMonth()->format('Y-m-d H:i:s'),
    ]);

    $response->assertRedirect(route('admin.tenants.index'));
    $this->assertDatabaseHas('tenants', [
        'id' => $tenant->id,
        'subscription_plan' => 'professional',
        'subscription_price' => '49.95',
        'subscription_status' => 'active',
    ]);
});

it('starts a seven-day trial and blocks workspace access after it expires', function () {
    [$tenant, $owner] = createManagedTenant();

    expect($tenant->subscription_status)->toBe('trialing')
        ->and($tenant->trial_ends_at->isSameDay(now()->addDays(7)))->toBeTrue();

    $tenant->update(['trial_ends_at' => now()->subSecond()]);

    $this->actingAs($owner)->get(route('dashboard'))->assertForbidden();
});

it('suspends a tenant and denies its members access to workspace routes', function () {
    $admin = createPlatformAdmin();
    [$tenant, , $member] = createManagedTenant();

    $response = $this->actingAs($admin)->patch(route('admin.tenants.status', $tenant));

    $response->assertRedirect();
    $this->assertDatabaseHas('tenants', ['id' => $tenant->id, 'is_active' => false]);
    $this->actingAs($member)->get(route('dashboard'))->assertForbidden();
});

it('requires the exact tenant name before deleting tenant data', function () {
    $admin = createPlatformAdmin();
    [$tenant, $owner] = createManagedTenant();
    Portfolio::create(['user_id' => $owner->id]);

    $response = $this->actingAs($admin)->delete(route('admin.tenants.destroy', $tenant), [
        'confirmation' => 'Acme',
    ]);

    $response->assertSessionHasErrors('confirmation');
    $this->assertDatabaseHas('tenants', ['id' => $tenant->id]);
});

it('deletes tenant members and their portfolio only after exact-name confirmation', function () {
    $admin = createPlatformAdmin();
    [$tenant, $owner, $member] = createManagedTenant();
    $portfolio = Portfolio::create(['user_id' => $owner->id]);

    $response = $this->actingAs($admin)->delete(route('admin.tenants.destroy', $tenant), [
        'confirmation' => $tenant->name,
    ]);

    $response->assertRedirect(route('admin.tenants.index'));
    $this->assertDatabaseMissing('tenants', ['id' => $tenant->id]);
    $this->assertDatabaseMissing('users', ['id' => $owner->id]);
    $this->assertDatabaseMissing('users', ['id' => $member->id]);
    $this->assertDatabaseMissing('portfolios', ['id' => $portfolio->id]);
});

it('protects the platform-admin tenant from deletion', function () {
    $admin = createPlatformAdmin();
    $tenant = $admin->tenant;

    $response = $this->actingAs($admin)->delete(route('admin.tenants.destroy', $tenant), [
        'confirmation' => $tenant->name,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('tenants', ['id' => $tenant->id]);
});
