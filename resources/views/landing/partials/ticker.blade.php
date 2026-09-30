<!-- Scrolling market ticker -->
<section id="market" class="relative border-y border-white/10 bg-ink-800/60 py-5 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4">
            <span class="inline-flex shrink-0 items-center gap-2 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-slate-400">
                <span class="h-1.5 w-1.5 rounded-full bg-mint-400"></span>
                Market tape
            </span>

            <span class="hidden shrink-0 items-center gap-4 text-[0.65rem] font-semibold uppercase tracking-[0.2em] sm:flex">
                <span class="text-mint-300">{{ $stats['advancing'] }} advancing</span>
                <span class="text-rose-300">{{ $stats['declining'] }} declining</span>
                <span class="text-slate-500">{{ $stats['stocks'] }} tracked</span>
            </span>
        </div>
    </div>

    <div class="marquee marquee--fade mt-4">
        <div class="marquee__track">
            @foreach ([0, 1] as $pass)
                <div class="flex shrink-0 items-center" @if ($pass === 1) aria-hidden="true" @endif>
                    @foreach ($quotes as $quote)
                        <div class="flex items-center gap-3 border-r border-white/5 px-6 py-1">
                            <span class="font-display text-sm font-bold text-white">{{ $quote['symbol'] }}</span>
                            <span class="font-mono text-sm text-slate-400">${{ number_format($quote['price'], 2) }}</span>
                            <span @class([
                                'inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold',
                                'bg-mint-500/15 text-mint-300' => $quote['change_percent'] >= 0,
                                'bg-rose-500/15 text-rose-300' => $quote['change_percent'] < 0,
                            ])>
                                <svg @class([
                                    'h-3 w-3',
                                    'rotate-0' => $quote['change_percent'] >= 0,
                                    'rotate-180' => $quote['change_percent'] < 0,
                                ]) fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5m0 0-5 5m5-5 5 5" />
                                </svg>
                                {{ number_format(abs($quote['change_percent']), 2) }}%
                            </span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
