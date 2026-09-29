<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Market Overview
                </span>
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('M d, Y h:i A') }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Market Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Stocks</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalStocks) }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Advancing</div>
                        <div class="text-2xl font-bold text-green-600 mt-1">{{ number_format($advancing) }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Declining</div>
                        <div class="text-2xl font-bold text-red-600 mt-1">{{ number_format($declining) }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Unchanged</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($unchanged) }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Sector Performance -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-6">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A3.055 3.055 0 0010.927 3H4.364a3.055 3.055 0 00-2.863 2.087L.21 13.624a3.055 3.055 0 002.863 2.087h6.564c.414 0 .81-.063 1.182-.178l5.89-2.944a3.055 3.055 0 001.663-2.718V6.722a3.055 3.055 0 00-1.663-2.718l-5.89-2.945z"/>
                            </svg>
                            Sector Performance
                        </h3>
                        @if($sectorPerformance->count() > 0)
                            <div class="space-y-4">
                                @foreach($sectorPerformance as $sector)
                                    <div class="group">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $sector->sector }}</span>
                                            <span class="text-sm font-semibold {{ $sector->avg_change_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($sector->avg_change_percent, 2) }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                            <div class="h-3 rounded-full {{ $sector->avg_change_percent >= 0 ? 'bg-gradient-to-r from-green-400 to-green-500' : 'bg-gradient-to-r from-red-400 to-red-500' }}" 
                                                 style="width: {{ min(abs($sector->avg_change_percent) * 5, 100) }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                            <span>{{ $sector->stock_count }} stocks</span>
                                            <span class="mx-2">•</span>
                                            <span>Vol: {{ number_format($sector->total_volume) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No sector data available</p>
                        @endif
                    </div>
                </div>

                <!-- Most Active -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-6">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            Most Active Stocks
                        </h3>
                        @if($mostActive->count() > 0)
                            <div class="space-y-3">
                                @foreach($mostActive as $stock)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-xs font-bold mr-3">
                                                {{ substr($stock->symbol, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $stock->symbol }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Vol: {{ number_format($stock->volume) }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($stock->current_price, 2) }}</div>
                                            <div class="text-xs {{ $stock->price_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($stock->price_change_percent, 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No active stocks data available</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Gainers -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-6">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            Top Gainers
                        </h3>
                        @if($topGainers->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Symbol</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Change</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($topGainers as $stock)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <div class="w-6 h-6 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-xs font-bold text-green-600 dark:text-green-400 mr-2">
                                                            {{ substr($stock->symbol, 0, 1) }}
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stock->symbol }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-right text-sm text-gray-900 dark:text-white">${{ number_format($stock->current_price, 2) }}</td>
                                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">+{{ number_format($stock->price_change_percent, 2) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No gainers data available</p>
                        @endif
                    </div>
                </div>

                <!-- Top Losers -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-6">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                            Top Losers
                        </h3>
                        @if($topLosers->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Symbol</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Change</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($topLosers as $stock)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <div class="w-6 h-6 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-xs font-bold text-red-600 dark:text-red-400 mr-2">
                                                            {{ substr($stock->symbol, 0, 1) }}
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stock->symbol }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-right text-sm text-gray-900 dark:text-white">${{ number_format($stock->current_price, 2) }}</td>
                                                <td class="px-4 py-3 text-right text-sm font-semibold text-red-600">{{ number_format($stock->price_change_percent, 2) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No losers data available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>