<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Transaction Details
                </span>
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">#{{ $transaction->id }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Navigation -->
            <div class="mb-6">
                <a href="{{ route('transactions.index') }}" 
                   class="group inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-2 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </div>
                    <span class="font-medium">Back to Transactions</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <!-- Stock Header -->
                            <div class="flex items-start justify-between mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                        {{ substr($transaction->stock->symbol, 0, 2) }}
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $transaction->stock->symbol }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->stock->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $transaction->type === 'buy' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300' }}">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $transaction->type === 'buy' ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"/>
                                        </svg>
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Transaction Details Grid -->
                            <div class="grid grid-cols-2 gap-6">
                                <!-- Quantity -->
                                <div class="group">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        Quantity
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                        {{ number_format($transaction->quantity) }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">shares</p>
                                </div>

                                <!-- Price per Share -->
                                <div class="group">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Price/Share
                                    </div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                        ${{ number_format($transaction->price_per_share, 2) }}
                                    </p>
                                </div>

                                <!-- Total Amount -->
                                <div class="group col-span-2">
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Total Amount
                                    </div>
                                    <div class="flex items-baseline">
                                        <p class="text-3xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                            ${{ number_format($transaction->total_amount, 2) }}
                                        </p>
                                        <span class="ml-2 text-sm text-gray-400 dark:text-gray-500">USD</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="space-y-6">
                    <!-- Status & Date Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Status & Timing</h4>
                            
                            <!-- Status -->
                            <div class="mb-5">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                        <span class="w-2 h-2 rounded-full bg-current mr-1.5 {{ $transaction->status === 'completed' ? 'animate-pulse' : '' }}"></span>
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-gradient-to-r from-green-400 to-green-500 h-1.5 rounded-full" style="width: {{ $transaction->status === 'completed' ? '100%' : '50%' }};"></div>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="space-y-3">
                                <div class="flex items-center text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Date</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $transaction->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Time</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $transaction->created_at->format('h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Summary Card -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-2xl text-white">
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-blue-100 uppercase tracking-wider mb-4">Transaction Summary</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-blue-100 text-sm">Shares</span>
                                    <span class="font-bold">{{ number_format($transaction->quantity) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-blue-100 text-sm">Rate</span>
                                    <span class="font-bold">${{ number_format($transaction->price_per_share, 2) }}</span>
                                </div>
                                <div class="border-t border-blue-400/30 pt-3 flex justify-between items-center">
                                    <span class="text-blue-100 text-sm font-medium">Total</span>
                                    <span class="font-bold text-lg">${{ number_format($transaction->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            @if($transaction->notes)
                <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Notes
                        </h4>
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $transaction->notes }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>