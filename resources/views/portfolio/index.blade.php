<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Portfolio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Portfolio Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Value -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Portfolio Value</div>
                        <div class="text-3xl font-bold mt-2">${{ number_format($totalValue, 2) }}</div>
                        <div class="text-sm {{ $totalReturn >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                            ${{ number_format($totalReturn, 2) }} ({{ number_format($totalReturnPercent, 2) }}%)
                        </div>
                    </div>
                </div>

                <!-- Cash Balance -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Cash Available</div>
                        <div class="text-3xl font-bold mt-2">${{ number_format($portfolio->cash_balance, 2) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ number_format(($portfolio->cash_balance / $totalValue) * 100, 1) }}% of portfolio
                        </div>
                    </div>
                </div>

                <!-- Stocks Value -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Stocks Value</div>
                        <div class="text-3xl font-bold mt-2">${{ number_format($totalValue - $portfolio->cash_balance, 2) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ number_format((($totalValue - $portfolio->cash_balance) / $totalValue) * 100, 1) }}% of portfolio
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Holdings -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Current Holdings
                            </h3>
                            @if($holdings->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Symbol</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Shares</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Avg Cost</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Current</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Value</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">P/L</th>
                                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($holdings as $holding)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                    <td class="px-4 py-4">
                                                        <div class="flex items-center">
                                                            <div>
                                                                <div class="font-bold text-gray-900 dark:text-gray-100">
                                                                    {{ $holding->stock->symbol }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                    {{ Str::limit($holding->stock->name, 20) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 text-right text-sm text-gray-900 dark:text-gray-100">
                                                        {{ number_format($holding->quantity) }}
                                                    </td>
                                                    <td class="px-4 py-4 text-right text-sm text-gray-900 dark:text-gray-100">
                                                        ${{ number_format($holding->average_cost, 2) }}
                                                    </td>
                                                    <td class="px-4 py-4 text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        ${{ number_format($holding->stock->current_price, 2) }}
                                                    </td>
                                                    <td class="px-4 py-4 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        ${{ number_format($holding->current_value, 2) }}
                                                    </td>
                                                    <td class="px-4 py-4 text-right">
                                                        <div class="text-sm {{ $holding->unrealized_pnl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                            ${{ number_format($holding->unrealized_pnl, 2) }}
                                                        </div>
                                                        <div class="text-xs {{ $holding->unrealized_pnl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ number_format($holding->unrealized_pnl_percent, 2) }}%
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <div class="flex items-center justify-center space-x-2">
                                                            <a href="{{ route('portfolio.holding', $holding) }}" 
                                                               class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                                                                Details
                                                            </a>
                                                            <a href="{{ route('transactions.create', $holding->stock) }}?type=sell" 
                                                               class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition">
                                                                Sell
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                                    No holdings yet. <a href="{{ route('stocks.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Start investing!</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Sector Allocation -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Sector Allocation
                            </h3>
                            @if(count($sectorAllocation) > 0)
                                <div class="space-y-3">
                                    @foreach($sectorAllocation as $sector => $percent)
                                        <div>
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-700 dark:text-gray-300">{{ $sector }}</span>
                                                <span class="text-gray-900 dark:text-gray-100 font-medium">{{ number_format($percent, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No allocation data yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Top Performers -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Top Performers
                            </h3>
                            @if($topPerformers->count() > 0)
                                <div class="space-y-3">
                                    @foreach($topPerformers as $holding)
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $holding->stock->symbol }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    ${{ number_format($holding->current_value, 2) }}
                                                </div>
                                            </div>
                                            <div class="text-right text-green-600">
                                                <div class="font-medium">+${{ number_format($holding->unrealized_pnl, 2) }}</div>
                                                <div class="text-sm">+{{ number_format($holding->unrealized_pnl_percent, 2) }}%</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No holdings yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Worst Performers -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Worst Performers
                            </h3>
                            @if($worstPerformers->count() > 0)
                                <div class="space-y-3">
                                    @foreach($worstPerformers as $holding)
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $holding->stock->symbol }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    ${{ number_format($holding->current_value, 2) }}
                                                </div>
                                            </div>
                                            <div class="text-right text-red-600">
                                                <div class="font-medium">${{ number_format($holding->unrealized_pnl, 2) }}</div>
                                                <div class="text-sm">{{ number_format($holding->unrealized_pnl_percent, 2) }}%</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No holdings yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>