<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $stock->symbol }} - {{ $stock->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stock Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                ${{ number_format($stock->current_price, 2) }}
                            </div>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="text-lg {{ $stock->price_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    @if($stock->price_change !== null)
                                        ${{ number_format($stock->price_change, 2) }} ({{ number_format($stock->price_change_percent, 2) }}%)
                                    @else
                                        0.00 (0.00%)
                                    @endif
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    Today
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex space-x-3">
                            <a href="{{ route('transactions.create', $stock) }}" 
                               class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Trade {{ $stock->symbol }}
                            </a>
                            <a href="{{ route('stocks.index') }}" 
                               class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                Back to Stocks
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Stock Chart -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Price Chart
                            </h3>
                            <div id="chart-container" class="h-80">
                                <canvas id="priceChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Transactions for this Stock -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Your Recent Transactions
                            </h3>
                            @if($stock->transactions->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Qty</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($stock->transactions as $transaction)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $transaction->created_at->format('M d, Y H:i') }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded {{ $transaction->type === 'buy' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                            {{ ucfirst($transaction->type) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ number_format($transaction->quantity) }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                        ${{ number_format($transaction->price_per_share, 2) }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        ${{ number_format($transaction->total_amount, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                                    No transactions for this stock yet.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Stock Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Stock Information
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Company Name</div>
                                    <div class="text-gray-900 dark:text-gray-100">{{ $stock->name }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Symbol</div>
                                    <div class="text-gray-900 dark:text-gray-100 font-mono">{{ $stock->symbol }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Sector</div>
                                    <div class="text-gray-900 dark:text-gray-100">{{ $stock->sector ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Industry</div>
                                    <div class="text-gray-900 dark:text-gray-100">{{ $stock->industry ?? 'N/A' }}</div>
                                </div>
                                @if($stock->description)
                                    <div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Description</div>
                                        <div class="text-gray-900 dark:text-gray-100 text-sm mt-1">
                                            {{ Str::limit($stock->description, 200) }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Key Statistics -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Key Statistics
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Open</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">${{ number_format($stock->opening_price ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Previous Close</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">${{ number_format($stock->previous_close ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Day High</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">${{ number_format($stock->day_high ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Day Low</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">${{ number_format($stock->day_low ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Volume</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ number_format($stock->volume) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Market Cap</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        @if($stock->market_cap)
                                            @if($stock->market_cap >= 1e12)
                                                ${{ number_format($stock->market_cap / 1e12, 2) }}T
                                            @elseif($stock->market_cap >= 1e9)
                                                ${{ number_format($stock->market_cap / 1e9, 2) }}B
                                            @else
                                                ${{ number_format($stock->market_cap / 1e6, 2) }}M
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">P/E Ratio</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">{{ $stock->pe_ratio ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Dividend Yield</span>
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ $stock->dividend_yield ? number_format($stock->dividend_yield * 100, 2) . '%' : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($historicalData->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('priceChart').getContext('2d');
                const data = @json($historicalData);
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => new Date(d.timestamp).toLocaleString()),
                        datasets: [{
                            label: 'Price',
                            data: data.map(d => d.close),
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false
                            }
                        }
                    }
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
</x-app-layout>