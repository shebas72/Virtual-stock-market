<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminTenantController extends Controller
{
    public function index(): View
    {
        $tenants = Tenant::query()
            ->withCount('users')
            ->with(['users' => fn ($query) => $query
                ->where('tenant_role', 'owner')
                ->select('id', 'tenant_id', 'name', 'email')])
            ->orderByDesc('created_at')
            ->paginate(20);

        $metrics = [
            'total' => Tenant::count(),
            'active' => Tenant::where('is_active', true)->count(),
            'suspended' => Tenant::where('is_active', false)->count(),
            'users' => User::whereNotNull('tenant_id')->count(),
        ];

        return view('admin.tenants.index', compact('tenants', 'metrics'));
    }

    public function edit(Tenant $tenant): View
    {
        return view('admin.tenants.edit', [
            'tenant' => $tenant,
            'plans' => Tenant::SUBSCRIPTION_PLANS,
        ]);
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('tenants', 'slug')->ignore($tenant->id)],
            'subscription_plan' => ['required', Rule::in(array_keys(Tenant::SUBSCRIPTION_PLANS))],
            'subscription_price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'subscription_status' => ['required', Rule::in(['trialing', 'active', 'expired'])],
            'trial_ends_at' => ['nullable', 'required_if:subscription_status,trialing', 'date'],
            'subscription_ends_at' => ['nullable', 'date'],
        ]);

        $tenant->update([
            'name' => $validated['name'],
            'slug' => Str::lower($validated['slug']),
            'subscription_plan' => $validated['subscription_plan'],
            'subscription_price' => $validated['subscription_price'],
            'subscription_status' => $validated['subscription_status'],
            'trial_ends_at' => $validated['trial_ends_at'] ?? null,
            'subscription_ends_at' => $validated['subscription_ends_at'] ?? null,
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated.');
    }

    public function toggleStatus(Tenant $tenant): RedirectResponse
    {
        $tenant->update(['is_active' => ! $tenant->is_active]);

        $status = $tenant->is_active ? 'reactivated' : 'suspended';

        return back()->with('success', "Tenant {$status}.");
    }

    public function destroy(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validate([
            'confirmation' => ['required', Rule::in([$tenant->name])],
        ], [
            'confirmation.in' => 'Enter the tenant name exactly to confirm deletion.',
        ]);

        if ($tenant->users()->where('role', 'admin')->exists()) {
            return back()->with('error', 'This tenant contains a platform admin account and cannot be deleted.');
        }

        DB::transaction(fn () => $tenant->delete());

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant and its users, portfolios, and trading history were permanently deleted.');
    }
}
