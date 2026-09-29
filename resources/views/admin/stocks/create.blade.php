<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Stock') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.stocks.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Symbol -->
                            <div>
                                <label for="symbol" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Symbol *</label>
                                <input type="text" name="symbol" id="symbol" value="{{ old('symbol') }}" required
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('symbol') border-red-500 @enderror">
                                @error('symbol')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sector -->
                            <div>
                                <label for="sector" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sector</label>
                                <input type="text" name="sector" id="sector" value="{{ old('sector') }}"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Industry -->
                            <div>
                                <label for="industry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Industry</label>
                                <input type="text" name="industry" id="industry" value="{{ old('industry') }}"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Current Price -->
                            <div>
                                <label for="current_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Price *</label>
                                <input type="number" name="current_price" id="current_price" value="{{ old('current_price') }}" step="0.01" min="0.01" required
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_price') border-red-500 @enderror">
                                @error('current_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Previous Close -->
                            <div>
                                <label for="previous_close" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Previous Close</label>
                                <input type="number" name="previous_close" id="previous_close" value="{{ old('previous_close') }}" step="0.01" min="0"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Opening Price -->
                            <div>
                                <label for="opening_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening Price</label>
                                <input type="number" name="opening_price" id="opening_price" value="{{ old('opening_price') }}" step="0.01" min="0"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Day High -->
                            <div>
                                <label for="day_high" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Day High</label>
                                <input type="number" name="day_high" id="day_high" value="{{ old('day_high') }}" step="0.01" min="0"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Day Low -->
                            <div>
                                <label for="day_low" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Day Low</label>
                                <input type="number" name="day_low" id="day_low" value="{{ old('day_low') }}" step="0.01" min="0"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Volume -->
                            <div>
                                <label for="volume" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Volume</label>
                                <input type="number" name="volume" id="volume" value="{{ old('volume') }}" min="0"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Market Cap -->
                            <div>
                                <label for="market_cap" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Market Cap</label>
                                <input type="number" name="market_cap" id="market_cap" value="{{ old('market_cap') }}" step="0.01" min="0"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- P/E Ratio -->
                            <div>
                                <label for="pe_ratio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">P/E Ratio</label>
                                <input type="number" name="pe_ratio" id="pe_ratio" value="{{ old('pe_ratio') }}" step="0.01" min="0"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>

                            <!-- Dividend Yield -->
                            <div>
                                <label for="dividend_yield" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dividend Yield (%)</label>
                                <input type="number" name="dividend_yield" id="dividend_yield" value="{{ old('dividend_yield') }}" step="0.0001" min="0" max="100"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                        </div>

                        <!-- Submit -->
                        <div class="mt-6 flex items-center justify-between">
                            <a href="{{ route('admin.stocks') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Cancel</a>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Create Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>