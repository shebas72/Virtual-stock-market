@php
    $movers = collect($quotes)
        ->sortByDesc(fn (array $quote) => abs($quote['change_percent']))
        ->take(6);

    $sectorAllocation = collect($quotes)
        ->groupBy('sector')
        ->map(fn ($group) => $group->count())
        ->sortDesc();

    $sectorTotal = max($sectorAllocation->sum(), 1);
@endphp

<!-- Product showcase -->
<section class="relative py-20 sm:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -right-40 top-20 h-[32rem] w-[32rem] rounded-full bg-cyan-500/10 blur-[140px]"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-landing.section-heading
            eyebrow="Inside the terminal"
            title="Market overview, movers and allocation at a glance"
            description="The same screens your team will live in: a live tape, gainers and losers, sector rotation and the numbers behind every holding."
        />

        <div class="mt-14 grid gap-6 lg:grid-cols-3">
            <!-- Movers -->
            <div x-reveal class="overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.035] shadow-panel backdrop-blur-xl lg:col-span-2">
                <div class="flex items-center justify-between gap-4 border-b border-white/10 px-6 py-5">
                    <div>
                        <h3 class="font-display text-lg font-semibold text-white">Today's movers</h3>
                        <p class="mt-1 text-xs text-slate-500">Biggest percentage swings across the tracked universe</p>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-3 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-mint-400"></span>
                        Live
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/[0.06]">
                        <thead>
                            <tr class="text-left text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-slate-500">
                                <th scope="col" class="px-6 py-3">Instrument</th>
                                <th scope="col" class="px-6 py-3 text-right">Last</th>
                                <th scope="col" class="px-6 py-3 text-right">Change</th>
                                <th scope="col" class="hidden px-6 py-3 text-right sm:table-cell">Momentum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.06]">
                            @foreach ($movers as $quote)
                                <tr class="transition duration-300 hover:bg-white/[0.04]">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500/40 to-cyan-500/30 text-[0.68rem] font-bold text-white">
                                                {{ Str::substr($quote['symbol'], 0, 3) }}
                                            </span>
                                            <span>
                                                <span class="block text-sm font-semibold text-white">{{ $quote['symbol'] }}</span>
                                                <span class="block max-w-[10rem] truncate text-xs text-slate-500">{{ $quote['name'] }}</span>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono text-sm text-slate-200">
                                        ${{ number_format($quote['price'], 2) }}
                                    </td>
                                    <td @class([
                                        'px-6 py-4 text-right text-sm font-semibold',
                                        'text-mint-400' => $quote['change_percent'] >= 0,
                                        'text-rose-400' => $quote['change_percent'] < 0,
                                    ])>
                                        {{ $quote['change_percent'] >= 0 ? '+' : '' }}{{ number_format($quote['change_percent'], 2) }}%
                                    </td>
                                    <td class="hidden px-6 py-4 sm:table-cell">
                                        <div class="ms-auto flex h-2 w-28 overflow-hidden rounded-full bg-white/[0.06]">
                                            <span
                                                @class([
                                                    'h-full rounded-full',
                                                    'bg-gradient-to-r from-mint-400 to-cyan-400' => $quote['change_percent'] >= 0,
                                                    'bg-gradient-to-r from-rose-500 to-orange-400' => $quote['change_percent'] < 0,
                                                ])
                                                style="width: {{ min(100, max(12, round(abs($quote['change_percent']) * 14))) }}%"
                                            ></span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sector coverage -->
            <div x-reveal style="transition-delay: 140ms" class="rounded-[2rem] border border-white/10 bg-white/[0.035] p-6 shadow-panel backdrop-blur-xl">
                <h3 class="font-display text-lg font-semibold text-white">Sector coverage</h3>
                <p class="mt-1 text-xs text-slate-500">How the tracked universe is spread</p>

                <div class="mt-6 space-y-4">
                    @foreach ($sectorAllocation as $sector => $count)
                        <div>
                            <div class="flex items-center justify-between text-xs font-medium">
                                <span class="text-slate-300">{{ $sector }}</span>
                                <span class="text-slate-500">{{ $count }} {{ Str::plural('stock', $count) }}</span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-white/[0.06]">
                                <span
                                    class="block h-full rounded-full bg-gradient-to-r from-brand-400 to-cyan-400"
                                    style="width: {{ round(($count / $sectorTotal) * 100) }}%"
                                ></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-7 rounded-2xl border border-white/10 bg-ink-800/70 p-4">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-500">Portfolio snapshot</p>
                    <dl class="mt-3 space-y-2.5 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-400">Total value</dt>
                            <dd class="font-mono font-semibold text-white">$112,480.32</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-400">Unrealised P&amp;L</dt>
                            <dd class="font-mono font-semibold text-mint-300">+$12,480.32</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-400">Cash available</dt>
                            <dd class="font-mono font-semibold text-white">$64,208.10</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</section>
