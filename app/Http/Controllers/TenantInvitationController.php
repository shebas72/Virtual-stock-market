<?php

namespace App\Http\Controllers;

use App\Models\TenantInvitation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TenantInvitationController extends Controller
{
    public function show(string $token): View
    {
        $invitation = $this->findOpenInvitation($token);
        abort_unless($invitation->tenant->is_active, 403, 'This workspace is suspended.');

        return view('team.accept-invitation', compact('invitation', 'token'));
    }

    public function accept(Request $request, string $token): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($validated, $token) {
            $invitation = TenantInvitation::query()
                ->with('tenant')
                ->where('token_hash', hash('sha256', $token))
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($invitation->tenant->is_active, 403, 'This workspace is suspended.');

            abort_if(User::where('email', $invitation->email)->exists(), 409, 'This email already has an account. Sign in to that account instead.');
            abort_unless($invitation->tenant->hasAvailableUserSlot(false), 403, 'This workspace has reached the user limit for its subscription.');

            $user = User::create([
                'name' => $validated['name'],
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'role' => 'user',
                'tenant_id' => $invitation->tenant_id,
                'tenant_role' => 'member',
                'is_active' => true,
            ]);

            $invitation->update(['accepted_at' => now()]);

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function findOpenInvitation(string $token): TenantInvitation
    {
        $invitation = TenantInvitation::with('tenant')
            ->where('token_hash', hash('sha256', $token))
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        abort_unless($invitation->tenant->is_active, 403, 'This workspace is suspended.');

        return $invitation;
    }
}
