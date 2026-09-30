<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- System Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</div>
                        <div class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $stats['total_users'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Stocks</div>
                        <div class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $stats['total_stocks'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Trades</div>
                        <div class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $stats['total_trades'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Portfolio Value</div>
                        <div class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">${{ number_format($stats['total_portfolio_value'], 2) }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cash in Market</div>
                        <div class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">${{ number_format($stats['total_cash_in_market'], 2) }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Stocks Value</div>
                        <div class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">${{ number_format($stats['total_stocks_value'], 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Users -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Users</h3>
                        @if($recentUsers->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentUsers as $user)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                ${{ number_format($user->portfolio->total_value ?? 0, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $user->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No users yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Top Traders -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Top Traders</h3>
                        @if($topTraders->count() > 0)
                            <div class="space-y-3">
                                @foreach($topTraders as $index => $trader)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-lg font-bold text-gray-500 dark:text-gray-400">#{{ $index + 1 }}</span>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $trader->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $trader->portfolio->total_trades }} trades</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-semibold {{ $trader->portfolio->portfolio_return_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($trader->portfolio->portfolio_return_percent, 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No traders yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Transactions</h3>
                    @if($recentTransactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Time</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">User</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Stock</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Qty</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentTransactions as $transaction)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $transaction->created_at->format('M d, H:i') }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $transaction->user->name }}</td>
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->stock->symbol }}</td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $transaction->type === 'buy' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-right text-gray-900 dark:text-gray-100">{{ number_format($transaction->quantity) }}</td>
                                            <td class="px-4 py-2 text-sm text-right font-medium text-gray-900 dark:text-gray-100">${{ number_format($transaction->total_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No transactions yet.</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="{{ route('admin.stocks') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition">
                    Manage Stocks
                </a>
                <a href="{{ route('admin.users') }}" class="bg-green-600 text-white p-4 rounded-lg text-center hover:bg-green-700 transition">
                    Manage Users
                </a>
                <a href="{{ route('admin.tenants.index') }}" class="bg-cyan-700 text-white p-4 rounded-lg text-center hover:bg-cyan-800 transition">
                    Manage Tenants
                </a>
                <a href="{{ route('admin.market') }}" class="bg-purple-600 text-white p-4 rounded-lg text-center hover:bg-purple-700 transition">
                    Market Controls
                </a>
                <a href="{{ route('admin.transactions') }}" class="bg-orange-600 text-white p-4 rounded-lg text-center hover:bg-orange-700 transition">
                    View All Trades
                </a>
            </div>
        </div>
    </div>
</x-app-layout>