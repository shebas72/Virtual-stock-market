<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Workspace Subscription</h2>
            @if($tenant->hasValidSubscription())
                <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Back to dashboard</a>
            @endif
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-900 px-4 py-3 rounded" role="status">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-900 px-4 py-3 rounded" role="alert">{{ $errors->first() }}</div>
            @endif

            <section class="grid gap-6 border-b border-gray-200 pb-6 dark:border-gray-700 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Current plan</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $tenant->subscriptionPlan?->name ?? $tenant->subscription_plan }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                    <div class="mt-1 font-medium text-gray-900 dark:text-white">{{ ucfirst($tenant->subscription_status) }}</div>
                    @if($tenant->subscription_status === 'trialing' && $tenant->trial_ends_at)
                        <div class="text-sm text-gray-500 dark:text-gray-400">Trial ends {{ $tenant->trial_ends_at->format('M d, Y') }}</div>
                    @elseif($tenant->subscription_ends_at)
                        <div class="text-sm text-gray-500 dark:text-gray-400">Renews/ends {{ $tenant->subscription_ends_at->format('M d, Y') }}</div>
                    @endif
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Users</div>
                    <div class="mt-1 font-medium text-gray-900 dark:text-white">{{ $userCount }} / {{ $tenant->subscriptionPlan?->user_limit ?? 'Unlimited' }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $pendingInvitationsCount }} pending invitation(s)</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Current price</div>
                    <div class="mt-1 font-medium text-gray-900 dark:text-white">${{ number_format((float) ($tenant->subscriptionPlan?->effectivePrice() ?? $tenant->subscription_price), 2) }}</div>
                </div>
            </section>

            <section>
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available plans</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $canManage ? 'Choose a plan to upgrade or downgrade this workspace.' : 'Contact your workspace owner to change plans.' }}</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($plans as $plan)
                        @php($isCurrentPlan = $tenant->subscription_plan_id === $plan->id)
                        <article class="flex flex-col border {{ $isCurrentPlan ? 'border-teal-600 ring-1 ring-teal-600' : 'border-gray-200 dark:border-gray-700' }} bg-white dark:bg-gray-800 p-5">
                            <div class="flex items-start justify-between gap-3">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $plan->name }}</h4>
                                @if($isCurrentPlan)
                                    <span class="text-xs font-semibold uppercase text-teal-700 dark:text-teal-300">Current</span>
                                @endif
                            </div>
                            <div class="mt-4 flex items-baseline gap-2">
                                @if($plan->discounted_price !== null)
                                    <span class="text-sm text-gray-500 line-through dark:text-gray-400">${{ number_format((float) $plan->price, 2) }}</span>
                                    <span class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format((float) $plan->discounted_price, 2) }}</span>
                                @else
                                    <span class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format((float) $plan->price, 2) }}</span>
                                @endif
                                <span class="text-sm text-gray-500 dark:text-gray-400">/ {{ $plan->termLabel() }}</span>
                            </div>
                            <dl class="mt-5 space-y-2 border-t border-gray-200 pt-4 text-sm dark:border-gray-700">
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500 dark:text-gray-400">Users included</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $plan->user_limit }}</dd>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-gray-500 dark:text-gray-400">Billing term</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white">{{ $plan->termLabel() }}</dd>
                                </div>
                            </dl>
                            @if($canManage && ! $isCurrentPlan)
                                <form action="{{ route('subscription.change', $plan) }}" method="POST" class="mt-5">
                                    @csrf
                                    <button type="submit" class="w-full border border-teal-700 px-4 py-2 text-sm font-medium text-teal-800 hover:bg-teal-50 dark:border-teal-400 dark:text-teal-300 dark:hover:bg-gray-700">Choose {{ $plan->name }}</button>
                                </form>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>

            <p class="text-xs text-gray-500 dark:text-gray-400">Payments are handled outside this application.</p>
        </div>
    </div>
</x-app-layout>