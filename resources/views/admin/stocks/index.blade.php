<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Stocks') }}
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">All Stocks</h3>
                        <a href="{{ route('admin.stocks.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            + Add New Stock
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Symbol</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sector</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Change</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Trades</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($stocks as $stock)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-gray-100">{{ $stock->symbol }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $stock->name }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $stock->sector ?? '-' }}</td>
                                        <td class="px-4 py-4 text-sm text-right font-medium text-gray-900 dark:text-gray-100">${{ number_format($stock->current_price, 2) }}</td>
                                        <td class="px-4 py-4 text-sm text-right {{ $stock->price_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            ${{ number_format($stock->price_change, 2) }} ({{ number_format($stock->price_change_percent, 2) }}%)
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $stock->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ $stock->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-center text-gray-500 dark:text-gray-400">{{ $stock->transactions_count }}</td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.stocks.edit', $stock) }}" class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">Edit</a>
                                                <form action="{{ route('admin.stocks.toggle', $stock) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2 py-1 {{ $stock->is_active ? 'bg-yellow-600' : 'bg-green-600' }} text-white text-xs rounded hover:opacity-90">
                                                        {{ $stock->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.stocks.delete', $stock) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No stocks found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

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