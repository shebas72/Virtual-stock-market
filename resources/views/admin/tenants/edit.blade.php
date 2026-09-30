<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Tenant</h2>
            <a href="{{ route('admin.tenants.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Back to tenants</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <section class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                @if($errors->any())
                    <div class="mb-5 bg-red-100 border border-red-300 text-red-900 px-4 py-3 rounded" role="alert">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant name</label>
                        <input id="name" name="name" required maxlength="120" value="{{ old('name', $tenant->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant slug</label>
                        <input id="slug" name="slug" required maxlength="255" value="{{ old('slug', $tenant->slug) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-5">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Subscription</h3>
                    </div>
                    <div>
                        <label for="subscription_plan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan</label>
                        <select id="subscription_plan_id" name="subscription_plan_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" @selected((int) old('subscription_plan_id', $tenant->subscription_plan_id) === $plan->id)>{{ $plan->name }} · ${{ number_format((float) $plan->effectivePrice(), 2) }} / {{ $plan->termLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="subscription_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subscription status</label>
                        <select id="subscription_status" name="subscription_status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach(['trialing' => 'Free trial', 'active' => 'Active', 'expired' => 'Expired'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('subscription_status', $tenant->subscription_status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="trial_ends_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trial ends</label>
                        <input id="trial_ends_at" name="trial_ends_at" type="datetime-local" value="{{ old('trial_ends_at', $tenant->trial_ends_at?->format('Y-m-d\\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label for="subscription_ends_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subscription ends (optional)</label>
                        <input id="subscription_ends_at" name="subscription_ends_at" type="datetime-local" value="{{ old('subscription_ends_at', $tenant->subscription_ends_at?->format('Y-m-d\\TH:i')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save changes</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>