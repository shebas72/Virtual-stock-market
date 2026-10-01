<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Subscription Payments</h2>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.subscription-plans.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Payment settings</a>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Admin dashboard</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-900 px-4 py-3 rounded" role="status">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Collected</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $currency }} {{ number_format($metrics['collected'], 2) }}</div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Paid payments</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $metrics['paid'] }}</div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $metrics['pending'] }}</div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total attempts</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $metrics['total'] }}</div>
                </div>
            </div>

            <section class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Online payments: {{ $paymentsEnabled ? 'Enabled' : 'Disabled' }}</span>
                    @foreach($gatewayToggles as $gateway => $enabled)
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $enabled ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ ucfirst($gateway) }}: {{ $enabled ? 'On' : 'Off' }}@if(! ($gatewayConfigured[$gateway] ?? false)) (sandbox)@endif
                        </span>
                    @endforeach
                    <a href="{{ route('admin.subscription-plans.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Change</a>
                </div>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                <form action="{{ route('admin.payments.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div>
                        <label for="gateway" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gateway</label>
                        <select id="gateway" name="gateway" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All gateways</option>
                            @foreach(\App\Services\PaymentGatewayService::gateways() as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['gateway'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">All statuses</option>
                            @foreach(['pending' => 'Pending', 'completed' => 'Paid', 'cancelled' => 'Cancelled', 'failed' => 'Failed'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search user or workspace</label>
                        <input id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, email or reference" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Filter</button>
                        <a href="{{ route('admin.payments.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-gray-700 dark:border-gray-600 dark:text-gray-300">Reset</a>
                    </div>
                </form>
            </section>

            <section class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Paid by</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Workspace</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Plan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Gateway</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Reference</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ ($payment->paid_at ?? $payment->created_at)->format('M d, Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        <div class="font-medium">{{ $payment->user?->name ?? '—' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->user?->email }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $payment->tenant?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $payment->subscriptionPlan?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $payment->gatewayLabel() }}</td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900 dark:text-gray-100">{{ $payment->currency }} {{ number_format((float) $payment->amount, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded
                                            @if($payment->isCompleted()) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($payment->status === 'failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($payment->status === 'cancelled') bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200
                                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200
                                            @endif">
                                            {{ $payment->statusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $payment->reference ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">No payments recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <div>
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
