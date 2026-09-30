<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tenant Management</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Admin dashboard</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-900 px-4 py-3 rounded" role="status">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-900 px-4 py-3 rounded" role="alert">{{ session('error') }}</div>
            @endif

            <section class="grid grid-cols-2 lg:grid-cols-4 gap-4" aria-label="Tenant metrics">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total tenants</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($metrics['total']) }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Active</div>
                    <div class="mt-1 text-2xl font-semibold text-green-700 dark:text-green-400">{{ number_format($metrics['active']) }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Suspended</div>
                    <div class="mt-1 text-2xl font-semibold text-red-700 dark:text-red-400">{{ number_format($metrics['suspended']) }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Workspace users</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($metrics['users']) }}</div>
                </div>
            </section>

            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-900 px-4 py-3 rounded" role="alert">{{ $errors->first() }}</div>
            @endif

            <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Tenant</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Owner</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Users</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Subscription</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Registered</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tenants as $tenant)
                                @php($owner = $tenant->users->first())
                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $tenant->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $tenant->slug }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        @if($owner)
                                            <div>{{ $owner->name }}</div>
                                            <div class="text-gray-500 dark:text-gray-400">{{ $owner->email }}</div>
                                        @else
                                            <span class="text-gray-500">No owner</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($tenant->users_count) }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $tenant->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' }}">
                                            {{ $tenant->is_active ? 'Active' : 'Suspended' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        <div>{{ \App\Models\Tenant::SUBSCRIPTION_PLANS[$tenant->subscription_plan] ?? $tenant->subscription_plan }}</div>
                                        <div class="text-gray-500 dark:text-gray-400">{{ ucfirst($tenant->subscription_status) }} · ${{ number_format((float) $tenant->subscription_price, 2) }}/mo</div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $tenant->created_at->format('M d, Y') }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex min-w-64 flex-wrap items-center justify-end gap-2">
                                            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="px-3 py-1.5 text-sm text-blue-700 border border-blue-300 rounded hover:bg-blue-50 dark:text-blue-300 dark:border-blue-800 dark:hover:bg-gray-700">Edit</a>
                                            <form action="{{ route('admin.tenants.status', $tenant) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1.5 text-sm border rounded {{ $tenant->is_active ? 'text-amber-800 border-amber-300 hover:bg-amber-50 dark:text-amber-300 dark:border-amber-800' : 'text-green-800 border-green-300 hover:bg-green-50 dark:text-green-300 dark:border-green-800' }}">
                                                    {{ $tenant->is_active ? 'Suspend' : 'Reactivate' }}
                                                </button>
                                            </form>
                                            @if(!$tenant->users->contains(fn ($user) => $user->role === 'admin'))
                                                <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" class="flex items-center gap-1" onsubmit="return confirm('Permanently delete this tenant, all its users, portfolios, and trading history? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <label class="sr-only" for="confirm-tenant-{{ $tenant->id }}">Type {{ $tenant->name }} to confirm deletion</label>
                                                    <input id="confirm-tenant-{{ $tenant->id }}" type="text" name="confirmation" required placeholder="Type tenant name" class="w-32 rounded border-gray-300 text-xs dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                    <button type="submit" class="px-3 py-1.5 text-sm text-red-700 border border-red-300 rounded hover:bg-red-50 dark:text-red-300 dark:border-red-800">Delete</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-500" title="A tenant containing a platform admin cannot be deleted">Protected</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">No tenants registered.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tenants->hasPages())
                    <div class="border-t border-gray-200 dark:border-gray-700 p-4">{{ $tenants->links() }}</div>
                @endif
            </section>

            <p class="text-sm text-gray-500 dark:text-gray-400">Deleting a tenant permanently removes all of its member accounts, portfolios, and trading history.</p>
        </div>
    </div>
</x-app-layout>