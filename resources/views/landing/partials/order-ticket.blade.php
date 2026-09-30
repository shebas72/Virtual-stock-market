<div
    x-reveal
    class="mt-16 overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-white/[0.07] via-white/[0.03] to-transparent p-6 shadow-panel backdrop-blur-xl sm:p-10"
>
    <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full border border-mint-400/30 bg-mint-500/10 px-3.5 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-mint-300">
                Order ticket
            </span>

            <h3 class="mt-5 font-display text-2xl font-bold tracking-tight text-white sm:text-3xl">
                Every trade is validated before it fills
            </h3>

            <p class="mt-3 text-sm leading-relaxed text-slate-400 sm:text-base">
                The ticket quotes you the live price, the total cost and the cash you have left — then blocks the order
                if it would break your position or your balance.
            </p>

            <ul class="mt-6 space-y-3 text-sm text-slate-300">
                @foreach ([
                    'Live cost preview that updates as you type',
                    'Buying-power and share-availability checks',
                    'Quantity helpers for "max" position sizing',
                    'Optional trade notes for post-mortem reviews',
                ] as $point)
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-mint-500/15 text-mint-300">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                        {{ $point }}
                    </li>
                @endforeach
            </ul>

            <a
                href="{{ route('register') }}"
                class="group mt-8 inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-ink-900 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100"
            >
                Place your first trade
                <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <figure class="relative">
            <div class="pointer-events-none absolute -inset-6 -z-10 rounded-[2.5rem] bg-gradient-to-br from-brand-500/20 to-mint-500/20 blur-3xl" aria-hidden="true"></div>

            <div class="rounded-3xl border border-white/10 bg-ink-800/90 p-5 shadow-panel">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500/50 to-cyan-500/40 text-xs font-bold text-white">TSLA</span>
                        <div>
                            <p class="text-sm font-semibold text-white">Tesla, Inc.</p>
                            <p class="text-xs text-slate-500">Consumer · NASDAQ</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-mono text-sm font-semibold text-white">$337.55</p>
                        <p class="text-xs font-semibold text-mint-400">+2.79%</p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-2 gap-2 rounded-2xl border border-white/5 bg-white/[0.03] p-1">
                    <span class="rounded-xl bg-mint-500/20 py-2 text-center text-sm font-semibold text-mint-200">Buy</span>
                    <span class="py-2 text-center text-sm font-medium text-slate-400">Sell</span>
                </div>

                <dl class="mt-5 space-y-3 text-sm">
                    @foreach ([
                        ['Quantity', '48 shares', 'text-white'],
                        ['Order type', 'Market order', 'text-white'],
                        ['Estimated cost', '$16,202.40', 'text-white'],
                        ['Buying power after', '$83,797.60', 'text-slate-400'],
                    ] as [$label, $value, $valueClass])
                        <div class="flex items-center justify-between border-b border-white/5 pb-3 last:border-0 last:pb-0">
                            <dt class="text-slate-400">{{ $label }}</dt>
                            <dd class="font-mono font-semibold {{ $valueClass }}">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>

                <span class="mt-5 flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-mint-500 to-emerald-500 py-3 text-sm font-semibold text-ink-900" aria-hidden="true">
                    Review order
                </span>
            </div>

            <figcaption class="mt-4 text-center text-xs text-slate-500">
                Preview of the trading interface
            </figcaption>
        </figure>
    </div>
</div>
