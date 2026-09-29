<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Market Controls') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Market Status -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Market Status</div>
                        <div class="text-2xl font-bold mt-2 {{ $marketStatus['is_open'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $marketStatus['is_open'] ? 'OPEN' : 'CLOSED' }}
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Stocks</div>
                        <div class="text-2xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $marketStatus['total_stocks'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-green-600">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Advancing</div>
                        <div class="text-2xl font-bold mt-2">{{ $marketStatus['advancing'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-red-600">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Declining</div>
                        <div class="text-2xl font-bold mt-2">{{ $marketStatus['declining'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Market Controls</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <form action="{{ route('admin.market.refresh') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Refresh Live Stock Quotes
                            </button>
                        </form>
                        <form action="{{ route('admin.market.reset') }}" method="POST" onsubmit="return confirm('Reset all daily market data?');">
                            @csrf
                            <button type="submit" class="w-full px-6 py-3 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition">
                                🔄 Reset Daily Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Recent Market Data -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Market Data</h3>
                    @if($recentMarketData->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Time</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Stock</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Open</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">High</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Low</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Close</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Volume</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($recentMarketData as $data)
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $data->created_at->format('M d, H:i') }}</td>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $data->symbol }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">${{ number_format($data->open, 2) }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">${{ number_format($data->high, 2) }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">${{ number_format($data->low, 2) }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">${{ number_format($data->close, 2) }}</td>
                                            <td class="px-4 py-3 text-sm text-right text-gray-500 dark:text-gray-400">{{ number_format($data->volume) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">No market data yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>