<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Dashboard
                </span>
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('M d, Y') }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Portfolio Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
                <!-- Total Value -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 3.25rem; ">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Portfolio</span>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Value</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format($totalValue, 2) }}</div>
                        <div class="text-sm {{ $totalReturn >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $totalReturn >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"/>
                            </svg>
                            ${{ number_format($totalReturn, 2) }} ({{ number_format($totalReturnPercent, 2) }}%)
                        </div>
                    </div>
                </div>

                <!-- Cash Balance -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Liquid</span>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Cash Balance</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format($cashBalance, 2) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            Available for trading
                        </div>
                    </div>
                </div>

                <!-- Total Return -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl {{ $totalReturn >= 0 ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $totalReturn >= 0 ? 'text-green-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Performance</span>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Return</div>
                        <div class="text-2xl font-bold {{ $totalReturn >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            ${{ number_format($totalReturn, 2) }}
                        </div>
                        <div class="text-sm {{ $totalReturnPercent >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                            {{ number_format($totalReturnPercent, 2) }}% all-time
                        </div>
                    </div>
                </div>

                <!-- Total Trades -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Activity</span>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Trades</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalTrades }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            All time transactions
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recent Transactions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Recent Transactions
                                </h3>
                                <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center">
                                    View all
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                            @if($recentTransactions->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($recentTransactions as $transaction)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $transaction->created_at->format('M d') }}
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xs font-bold text-blue-600 dark:text-blue-400 mr-2">
                                                                {{ substr($transaction->stock->symbol, 0, 1) }}
                                                            </div>
                                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->stock->symbol }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->type === 'buy' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' }}">
                                                            {{ ucfirst($transaction->type) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ number_format($transaction->quantity) }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                        ${{ number_format($transaction->price_per_share, 2) }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        ${{ number_format($transaction->total_amount, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">No transactions yet.</p>
                                    <a href="{{ route('stocks.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm mt-1 inline-block">Start trading!</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Top Holdings -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-6">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A3.055 3.055 0 0010.927 3H4.364a3.055 3.055 0 00-2.863 2.087L.21 13.624a3.055 3.055 0 002.863 2.087h6.564c.414 0 .81-.063 1.182-.178l5.89-2.944a3.055 3.055 0 001.663-2.718V6.722a3.055 3.055 0 00-1.663-2.718l-5.89-2.945z"/>
                                </svg>
                                Top Holdings
                            </h3>
                            @if($topHoldings->count() > 0)
                                <div class="space-y-3">
                                    @foreach($topHoldings as $holding)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold mr-3">
                                                    {{ substr($holding->stock->symbol, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $holding->stock->symbol }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($holding->quantity) }} shares</div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-semibold text-gray-900 dark:text-white">${{ number_format($holding->current_value, 2) }}</div>
                                                <div class="text-sm {{ $holding->unrealized_pnl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    ${{ number_format($holding->unrealized_pnl, 2) }} ({{ number_format($holding->unrealized_pnl_percent, 2) }}%)
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A3.055 3.055 0 0010.927 3H4.364a3.055 3.055 0 00-2.863 2.087L.21 13.624a3.055 3.055 0 002.863 2.087h6.564c.414 0 .81-.063 1.182-.178l5.89-2.944a3.055 3.055 0 001.663-2.718V6.722a3.055 3.055 0 00-1.663-2.718l-5.89-2.945z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">No holdings yet.</p>
                                    <a href="{{ route('stocks.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm mt-1 inline-block">Browse stocks!</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Quick Actions
                            </h3>
                            <div class="space-y-3">
                                <a href="{{ route('stocks.index') }}" class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">Browse Stocks</span>
                                    <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                <a href="{{ route('market') }}" class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/40 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">Market Overview</span>
                                    <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                <a href="{{ route('leaderboard') }}" class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">Leaderboard</span>
                                    <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Top Gainers -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                                Top Gainers
                            </h3>
                            @if($topGainers->count() > 0)
                                <div class="space-y-3">
                                    @foreach($topGainers as $stock)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $stock->symbol }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($stock->current_price, 2) }}</div>
                                            </div>
                                            <div class="text-right text-green-600">
                                                <div class="font-semibold">+${{ number_format($stock->price_change, 2) }}</div>
                                                <div class="text-sm">+{{ number_format($stock->price_change_percent, 2) }}%</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No data available</p>
                            @endif
                        </div>
                    </div>

                    <!-- Top Losers -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                                Top Losers
                            </h3>
                            @if($topLosers->count() > 0)
                                <div class="space-y-3">
                                    @foreach($topLosers as $stock)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $stock->symbol }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($stock->current_price, 2) }}</div>
                                            </div>
                                            <div class="text-right text-red-600">
                                                <div class="font-semibold">${{ number_format($stock->price_change, 2) }}</div>
                                                <div class="text-sm">{{ number_format($stock->price_change_percent, 2) }}%</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No data available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>