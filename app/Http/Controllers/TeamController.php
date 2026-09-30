<?php

namespace App\Http\Controllers;

use App\Models\TenantInvitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        $tenant = $request->user()->tenant;

        return view('team.index', [
            'tenant' => $tenant,
            'members' => $tenant->users()->orderBy('name')->get(),
            'userCount' => $tenant->users()->count(),
            'userLimit' => $tenant->subscriptionPlan?->user_limit,
            'hasAvailableUserSlot' => $tenant->hasAvailableUserSlot(),
            'invitations' => $tenant->invitations()
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->latest()
                ->get(),
        ]);
    }

    public function invite(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'lowercase', 'max:255', 'unique:users,email'],
        ]);

        $token = Str::random(64);
        $tenant = $request->user()->tenant;
        if (! $tenant->hasAvailableUserSlot()) {
            return back()->withErrors(['email' => 'Your subscription has no available user seats. Upgrade your plan or remove a pending invitation.']);
        }

        $tenant->invitations()->where('email', $validated['email'])->delete();
        TenantInvitation::create([
            'tenant_id' => $tenant->id,
            'invited_by' => $request->user()->id,
            'email' => $validated['email'],
            'token_hash' => hash('sha256', $token),
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('invitation_url', URL::route('invitations.accept.show', $token));
    }

    public function createMember(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'lowercase', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $tenant = $request->user()->tenant;
        if (! $tenant->hasAvailableUserSlot()) {
            return back()->withErrors(['email' => 'Your subscription has no available user seats. Upgrade your plan or remove a pending invitation.']);
        }

        $tenant->users()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'tenant_role' => 'member',
            'is_active' => true,
        ]);

        return back()->with('success', 'Member account created. Share the email and initial password with the new member securely.');
    }

    public function toggleMemberStatus(Request $request, User $user): RedirectResponse
    {
        $member = $request->user()->tenant->users()
            ->where('tenant_role', 'member')
            ->findOrFail($user->id);

        $member->update(['is_active' => ! $member->is_active]);

        return back()->with('success', $member->is_active ? 'Member account reactivated.' : 'Member account suspended.');
    }
}
