<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Subscription Plans</h2>
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
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-900 px-4 py-3 rounded" role="alert">{{ $errors->first() }}</div>
            @endif

            <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Free trial</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Applies to new workspaces. Existing trial end dates are unchanged.</p>
                    </div>
                    <form action="{{ route('admin.subscription-plans.trial') }}" method="POST" class="mt-4 flex items-end gap-3 sm:mt-0">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="trial_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trial length (days)</label>
                            <input id="trial_days" name="trial_days" type="number" min="0" max="365" required value="{{ old('trial_days', $settings->trial_days) }}" class="mt-1 block w-32 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                    </form>
                </div>
            </section>

            <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add a plan</h3>
                <form action="{{ route('admin.subscription-plans.store') }}" method="POST" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6">
                    @csrf
                    <div>
                        <label for="new-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subscription name</label>
                        <input id="new-name" name="name" required maxlength="120" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="new-user-limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Users included</label>
                        <input id="new-user-limit" name="user_limit" type="number" min="1" required value="{{ old('user_limit', 5) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="new-duration-count" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Term length</label>
                        <div class="mt-1 flex gap-2">
                            <input id="new-duration-count" name="duration_count" type="number" min="1" max="365" required value="{{ old('duration_count', 1) }}" class="block w-20 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <select name="duration_unit" aria-label="Term unit" class="block min-w-0 flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="month" @selected(old('duration_unit', 'month') === 'month')>Months</option>
                                <option value="day" @selected(old('duration_unit') === 'day')>Days</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="new-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (USD)</label>
                        <input id="new-price" name="price" type="number" min="0" max="99999999.99" step="0.01" required value="{{ old('price', '0.00') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="new-discounted-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discounted price</label>
                        <input id="new-discounted-price" name="discounted_price" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('discounted_price') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add plan</button>
                    </div>
                </form>
            </section>

            <section class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Configured plans</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Starter values are editable examples. Set your prices and seat limits before tenants select a paid plan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Users</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Term</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Price (USD)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Discount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Default</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($plans as $plan)
                                <tr>
                                    <td colspan="7" class="p-0">
                                        <form id="plan-{{ $plan->id }}" action="{{ route('admin.subscription-plans.update', $plan) }}" method="POST" class="grid min-w-[900px] grid-cols-[1.3fr_0.7fr_1fr_1fr_1fr_0.7fr_1fr] items-end gap-3 px-4 py-4">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label for="plan-name-{{ $plan->id }}" class="sr-only">Subscription name</label>
                                                <input id="plan-name-{{ $plan->id }}" name="name" required maxlength="120" value="{{ $plan->name }}" class="block w-full rounded-md border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>
                                            <div>
                                                <label for="plan-users-{{ $plan->id }}" class="sr-only">Users included</label>
                                                <input id="plan-users-{{ $plan->id }}" name="user_limit" type="number" min="1" required value="{{ $plan->user_limit }}" class="block w-full rounded-md border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>
                                            <div class="flex gap-1">
                                                <label for="plan-count-{{ $plan->id }}" class="sr-only">Term length</label>
                                                <input id="plan-count-{{ $plan->id }}" name="duration_count" type="number" min="1" max="365" required value="{{ $plan->duration_count }}" class="w-16 rounded-md border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                <select name="duration_unit" aria-label="Term unit" class="min-w-0 flex-1 rounded-md border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                    <option value="month" @selected($plan->duration_unit === 'month')>Months</option>
                                                    <option value="day" @selected($plan->duration_unit === 'day')>Days</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="plan-price-{{ $plan->id }}" class="sr-only">Price (USD)</label>
                                                <input id="plan-price-{{ $plan->id }}" name="price" type="number" min="0" max="99999999.99" step="0.01" required value="{{ $plan->price }}" class="block w-full rounded-md border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>
                                            <div>
                                                <label for="plan-discount-{{ $plan->id }}" class="sr-only">Discounted price</label>
                                                <input id="plan-discount-{{ $plan->id }}" name="discounted_price" type="number" min="0" max="99999999.99" step="0.01" value="{{ $plan->discounted_price }}" class="block w-full rounded-md border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>
                                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                <input type="checkbox" name="is_default" value="1" @checked($plan->is_default) class="rounded border-gray-300 text-blue-600">
                                                Default
                                            </label>
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="submit" class="px-3 py-2 text-sm text-blue-700 border border-blue-300 rounded hover:bg-blue-50 dark:text-blue-300 dark:border-blue-800 dark:hover:bg-gray-700">Save</button>
                                                <span class="text-xs text-gray-500">{{ $plan->tenants_count }} tenant(s)</span>
                                            </div>
                                        </form>
                                        <div class="flex justify-end border-t border-gray-100 px-4 py-2 dark:border-gray-700">
                                            <form action="{{ route('admin.subscription-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this subscription plan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" @disabled($plan->tenants_count > 0) class="text-xs text-red-700 underline disabled:cursor-not-allowed disabled:text-gray-400 dark:text-red-300">Delete plan</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>