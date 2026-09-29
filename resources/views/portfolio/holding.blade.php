<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A3.055 3.055 0 0010.927 3H4.364a3.055 3.055 0 00-2.863 2.087L.21 13.624a3.055 3.055 0 002.863 2.087h6.564c.414 0 .81-.063 1.182-.178l5.89-2.944a3.055 3.055 0 001.663-2.718V6.722a3.055 3.055 0 00-1.663-2.718l-5.89-2.945z"/>
                    </svg>
                    {{ $holding->stock->symbol }} - Holding Details
                </span>
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $holding->stock->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Navigation -->
            <div class="mb-6">
                <a href="{{ route('portfolio.index') }}" 
                   class="group inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-2 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </div>
                    <span class="font-medium">Back to Portfolio</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2">
                    <!-- Stock Header Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700 mb-6">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                        {{ substr($holding->stock->symbol, 0, 2) }}
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $holding->stock->symbol }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $holding->stock->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($holding->stock->current_price, 2) }}</div>
                                    @if($holding->stock->price_change !== null)
                                        <div class="text-sm {{ $holding->stock->price_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            ${{ number_format(abs($holding->stock->price_change), 2) }} ({{ number_format(abs($holding->stock->price_change_percent), 2) }}%)
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Holding Stats Grid -->
                            <div class="grid grid-cols-2 gap-6">
                                <div class="group">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        Shares Owned
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                        {{ number_format($holding->quantity) }}
                                    </p>
                                </div>

                                <div class="group">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Average Cost
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                        ${{ number_format($holding->average_cost, 2) }}
                                    </p>
                                </div>

                                <div class="group col-span-2">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Current Value
                                    </div>
                                    <div class="flex items-baseline">
                                        <p class="text-3xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                            ${{ number_format($holding->current_value, 2) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="group">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                        Unrealized P/L
                                    </div>
                                    <p class="text-2xl font-bold {{ $holding->unrealized_pnl >= 0 ? 'text-green-600' : 'text-red-600' }} group-hover:opacity-80 transition-opacity duration-200">
                                        ${{ number_format($holding->unrealized_pnl, 2) }}
                                    </p>
                                </div>

                                <div class="group">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A3.055 3.055 0 0010.927 3H4.364a3.055 3.055 0 00-2.863 2.087L.21 13.624a3.055 3.055 0 002.863 2.087h6.564c.414 0 .81-.063 1.182-.178l5.89-2.944a3.055 3.055 0 001.663-2.718V6.722a3.055 3.055 0 00-1.663-2.718l-5.89-2.945z"/>
                                        </svg>
                                        Return %
                                    </div>
                                    <p class="text-2xl font-bold {{ $holding->unrealized_pnl_percent >= 0 ? 'text-green-600' : 'text-red-600' }} group-hover:opacity-80 transition-opacity duration-200">
                                        {{ number_format($holding->unrealized_pnl_percent, 2) }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Actions</h4>
                            <div class="space-y-3">
                                <a href="{{ route('transactions.create', $holding->stock) }}?type=buy" 
                                   class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200 font-semibold shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Buy More
                                </a>
                                <a href="{{ route('transactions.create', $holding->stock) }}?type=sell" 
                                   class="flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200 font-semibold shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                    </svg>
                                    Sell Shares
                                </a>
                                <a href="{{ route('stocks.show', $holding->stock) }}" 
                                   class="flex items-center justify-center px-4 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-semibold">
                                    View Stock Details
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Purchase Info</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">First Purchase</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $holding->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Invested</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($holding->quantity * $holding->average_cost, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Current Value</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($holding->current_value, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Return</span>
                                    <span class="text-sm font-bold {{ $holding->unrealized_pnl >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($holding->unrealized_pnl, 2) }} ({{ number_format($holding->unrealized_pnl_percent, 2) }}%)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>