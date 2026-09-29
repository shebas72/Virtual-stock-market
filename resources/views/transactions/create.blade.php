<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Trade {{ $stock->symbol }}
                </span>
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $stock->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Trading Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <form action="{{ route('transactions.store', $stock) }}" method="POST">
                                @csrf
                                <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                                
                                <!-- Trade Type Selection -->
                                <div class="mb-8">
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                        Trade Type
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="type" value="buy" checked 
                                                   class="peer sr-only">
                                            <div class="text-center py-4 px-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition-all duration-200 group-hover:border-gray-300 dark:group-hover:border-gray-500">
                                                <div class="flex items-center justify-center mb-2">
                                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-gray-900 dark:text-white">Buy</span>
                                            </div>
                                        </label>
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="type" value="sell" 
                                                   class="peer sr-only">
                                            <div class="text-center py-4 px-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 transition-all duration-200 group-hover:border-gray-300 dark:group-hover:border-gray-500">
                                                <div class="flex items-center justify-center mb-2">
                                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                                    </svg>
                                                </div>
                                                <span class="font-bold text-gray-900 dark:text-white">Sell</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Quantity Input -->
                                <div class="mb-8">
                                    <label for="quantity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                        Quantity (Shares)
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <div class="relative flex-1">
                                            <input type="number" name="quantity" id="quantity" min="1" 
                                                   max="{{ $maxBuyQuantity }}" value="1"
                                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-lg font-semibold focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow">
                                        </div>
                                        <button type="button" onclick="setMaxQuantity()" 
                                                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-medium">
                                            Max
                                        </button>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">
                                            @if($maxBuyQuantity > 0)
                                                Maximum you can buy: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ number_format($maxBuyQuantity) }}</span> shares
                                            @endif
                                        </span>
                                        @if($maxSellQuantity > 0)
                                            <span class="text-gray-500 dark:text-gray-400">
                                                You own: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ number_format($maxSellQuantity) }}</span> shares
                                            </span>
                                        @endif
                                    </div>
                                    @error('quantity')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Notes -->
                                <div class="mb-8">
                                    <label for="notes" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                        Notes <span class="text-gray-400 dark:text-gray-500 font-normal">(Optional)</span>
                                    </label>
                                    <textarea name="notes" id="notes" rows="3" 
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow resize-none"
                                              placeholder="Add any notes about this trade..."></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('stocks.show', $stock) }}" 
                                       class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors duration-200 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Cancel
                                    </a>
                                    <button type="submit" 
                                            class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Execute Trade
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Stock Info & Summary -->
                <div class="space-y-6">
                    <!-- Stock Info Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg mr-4">
                                    {{ substr($stock->symbol, 0, 2) }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $stock->symbol }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $stock->name }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Current Price</span>
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($stock->current_price, 2) }}</span>
                                </div>
                                @if($stock->price_change !== null)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Change</span>
                                        <span class="text-sm font-semibold {{ $stock->price_change >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            ${{ number_format(abs($stock->price_change), 2) }} ({{ number_format(abs($stock->price_change_percent), 2) }}%)
                                            {{ $stock->price_change >= 0 ? '↑' : '↓' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Cash Balance Card -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-2xl text-white">
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-blue-100 uppercase tracking-wider mb-2">Available Cash</h4>
                            <p class="text-3xl font-bold">${{ number_format($portfolio->cash_balance, 2) }}</p>
                        </div>
                    </div>

                    <!-- Estimated Total Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Estimated Total</h4>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white" id="estimated-total">
                                ${{ number_format($stock->current_price, 2) }}
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Based on current market price</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mt-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-red-700 dark:text-red-300 font-semibold mb-2">Please correct the following errors:</h3>
                            <ul class="list-disc list-inside text-red-600 dark:text-red-400 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        const pricePerShare = {{ $stock->current_price }};
        const maxBuyQty = {{ $maxBuyQuantity }};
        const maxSellQty = {{ $maxSellQuantity }};
        
        // Update estimated total when quantity changes
        document.getElementById('quantity').addEventListener('input', function() {
            const qty = parseInt(this.value) || 0;
            const total = qty * pricePerShare;
            document.getElementById('estimated-total').textContent = '$' + total.toFixed(2);
        });
        
        // Set max quantity based on trade type
        function setMaxQuantity() {
            const tradeType = document.querySelector('input[name="type"]:checked').value;
            const maxQty = tradeType === 'buy' ? maxBuyQty : maxSellQty;
            document.getElementById('quantity').value = maxQty;
            document.getElementById('quantity').dispatchEvent(new Event('input'));
        }
        
        // Update max and recalculate when trade type changes
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const maxQty = this.value === 'buy' ? maxBuyQty : maxSellQty;
                document.getElementById('quantity').max = maxQty;
                
                // Recalculate current value
                const currentQty = parseInt(document.getElementById('quantity').value) || 1;
                const validQty = Math.min(currentQty, maxQty);
                document.getElementById('quantity').value = validQty;
                document.getElementById('quantity').dispatchEvent(new Event('input'));
            });
        });
    </script>
</x-app-layout>