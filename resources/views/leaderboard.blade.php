<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                        Top Traders by Return
                    </h3>
                    
                    @if($leaderboard->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rank</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Trader</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Portfolio Value</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Return</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Return %</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Trades</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($leaderboard as $index => $entry)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $index < 3 ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    @if($index === 0)
                                                        <span class="text-2xl">🥇</span>
                                                    @elseif($index === 1)
                                                        <span class="text-2xl">🥈</span>
                                                    @elseif($index === 2)
                                                        <span class="text-2xl">🥉</span>
                                                    @else
                                                        <span class="text-lg font-bold text-gray-500 dark:text-gray-400">#{{ $index + 1 }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $entry['user']->name }}
                                            </td>
                                            <td class="px-4 py-4 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                ${{ number_format($entry['total_value'], 2) }}
                                            </td>
                                            <td class="px-4 py-4 text-right text-sm {{ $entry['return_percent'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                ${{ number_format(($entry['total_value'] - 100000), 2) }}
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                <span class="text-sm font-semibold {{ $entry['return_percent'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($entry['return_percent'], 2) }}%
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-right text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry['total_trades'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                            No traders yet. Be the first!
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>