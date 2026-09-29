<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Stock Market') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('stocks.index') }}" method="GET" class="flex flex-wrap gap-4">
                        <!-- Search -->
                        <div class="flex-1 min-w-64">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by symbol or name..." 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <!-- Sector Filter -->
                        <div class="w-48">
                            <select name="sector" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Sectors</option>
                                @foreach($sectors as $sector)
                                    <option value="{{ $sector }}" {{ request('sector') == $sector ? 'selected' : '' }}>
                                        {{ $sector }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Sort -->
                        <div class="w-48">
                            <select name="sort" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="symbol" {{ request('sort') == 'symbol' ? 'selected' : '' }}>Symbol</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="current_price" {{ request('sort') == 'current_price' ? 'selected' : '' }}>Price</option>
                                <option value="price_change_percent" {{ request('sort') == 'price_change_percent' ? 'selected' : '' }}>Change %</option>
                                <option value="volume" {{ request('sort') == 'volume' ? 'selected' : '' }}>Volume</option>
                            </select>
                        </div>
                        
                        <!-- Order -->
                        <div class="w-32">
                            <select name="order" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Asc</option>
                                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Desc</option>
                            </select>
                        </div>
                        
                        <!-- Submit -->
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Filter
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stocks Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Symbol</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sector</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Change</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Volume</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Market Cap</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($stocks as $stock)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $stock->symbol }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ Str::limit($stock->name, 30) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $stock->sector ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-900 dark:text-gray-100">
                                            ${{ number_format($stock->current_price, 2) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-right">
                                            @if($stock->price_change !== null)
                                                <span class="{{ $stock->price_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    ${{ number_format($stock->price_change, 2) }}
                                                    ({{ number_format($stock->price_change_percent, 2) }}%)
                                                </span>
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400">
                                            {{ number_format($stock->volume) }}
                                        </td>
                                        <td class="px-4 py-4 text-sm text-right text-gray-500 dark:text-gray-400">
                                            @if($stock->market_cap)
                                                @if($stock->market_cap >= 1e12)
                                                    ${{ number_format($stock->market_cap / 1e12, 2) }}T
                                                @elseif($stock->market_cap >= 1e9)
                                                    ${{ number_format($stock->market_cap / 1e9, 2) }}B
                                                @else
                                                    ${{ number_format($stock->market_cap / 1e6, 2) }}M
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-sm text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('stocks.show', $stock) }}" 
                                                   class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                                                    View
                                                </a>
                                                <a href="{{ route('transactions.create', $stock) }}" 
                                                   class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition">
                                                    Trade
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            No stocks found matching your criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($stocks->hasPages())
                        <div class="mt-6">
                            {{ $stocks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>