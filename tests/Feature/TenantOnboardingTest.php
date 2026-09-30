<?php

use App\Models\Portfolio;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

function createTenantOwner(string $name, string $slug): array
{
    $tenant = Tenant::create(['name' => $name, 'slug' => $slug]);
    $owner = User::factory()->create([
        'tenant_id' => $tenant->id,
        'tenant_role' => 'owner',
        'is_active' => true,
    ]);

    return [$tenant, $owner];
}

it('lets a workspace owner create an expiring member invitation', function () {
    [$tenant, $owner] = createTenantOwner('North Workspace', 'north-workspace');

    $response = $this->actingAs($owner)->post(route('team.invitations.store'), [
        'email' => 'member@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('invitation_url');
    $this->assertDatabaseHas('tenant_invitations', [
        'tenant_id' => $tenant->id,
        'email' => 'member@example.com',
        'accepted_at' => null,
    ]);
});

it('creates an invited account as a member of the inviting tenant', function () {
    [$tenant, $owner] = createTenantOwner('North Workspace', 'north-workspace');

    $inviteResponse = $this->actingAs($owner)->post(route('team.invitations.store'), [
        'email' => 'member@example.com',
    ]);

    $url = $inviteResponse->getSession()->get('invitation_url');
    $token = basename(parse_url($url, PHP_URL_PATH));

    $response = $this->post(route('invitations.accept', $token), [
        'name' => 'Workspace Member',
        'password' => 'a-strong-password',
        'password_confirmation' => 'a-strong-password',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'member@example.com',
        'tenant_id' => $tenant->id,
        'tenant_role' => 'member',
        'is_active' => true,
        'role' => 'user',
    ]);
});

it('lets a workspace owner directly create a member account', function () {
    [$tenant, $owner] = createTenantOwner('North Workspace', 'north-workspace');

    $response = $this->actingAs($owner)->post(route('team.members.store'), [
        'name' => 'Direct Member',
        'email' => 'direct-member@example.com',
        'password' => 'a-strong-password',
        'password_confirmation' => 'a-strong-password',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'name' => 'Direct Member',
        'email' => 'direct-member@example.com',
        'tenant_id' => $tenant->id,
        'tenant_role' => 'member',
        'role' => 'user',
        'is_active' => true,
    ]);
    expect(Hash::check('a-strong-password', User::where('email', 'direct-member@example.com')->firstOrFail()->password))->toBeTrue();
});

it('lets workspace owners suspend and reactivate their members', function () {
    [$tenant, $owner] = createTenantOwner('North Workspace', 'north-workspace');
    $member = User::factory()->create([
        'tenant_id' => $tenant->id,
        'tenant_role' => 'member',
    ]);

    $this->actingAs($owner)->patch(route('team.members.status', $member))->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $member->id, 'is_active' => false]);
    $this->actingAs($member)->get(route('dashboard'))->assertForbidden();

    $this->actingAs($owner)->patch(route('team.members.status', $member))->assertRedirect();
    $this->assertDatabaseHas('users', ['id' => $member->id, 'is_active' => true]);
});

it('restricts team management to tenant owners', function () {
    [$tenant, $owner] = createTenantOwner('North Workspace', 'north-workspace');
    $member = User::factory()->create([
        'tenant_id' => $tenant->id,
        'tenant_role' => 'member',
    ]);

    $this->actingAs($owner)->get(route('team.index'))->assertOk();
    $this->actingAs($member)->get(route('team.index'))->assertForbidden();
});

it('shows only the current tenant on the leaderboard', function () {
    [$tenant, $owner] = createTenantOwner('North Workspace', 'north-workspace');
    $otherTenant = Tenant::create(['name' => 'South Workspace', 'slug' => 'south-workspace']);
    $otherUser = User::factory()->create([
        'tenant_id' => $otherTenant->id,
        'tenant_role' => 'owner',
        'name' => 'Other Tenant Trader',
    ]);

    Portfolio::create(['user_id' => $owner->id]);
    Portfolio::create(['user_id' => $otherUser->id]);

    $response = $this->actingAs($owner)->get(route('leaderboard'));

    $response->assertOk();
    $response->assertSee($owner->name);
    $response->assertDontSee('Other Tenant Trader');
});
