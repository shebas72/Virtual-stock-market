<div
    x-data="liveTicker(@js($heroQuotes))"
    x-reveal
    class="relative mx-auto w-full max-w-xl lg:max-w-none"
>
    <!-- Halo -->
    <div class="pointer-events-none absolute -inset-8 -z-10 rounded-[3rem] bg-gradient-to-tr from-brand-500/25 via-cyan-500/15 to-mint-500/20 blur-3xl"></div>

    <!-- Terminal window -->
    <div class="relative rounded-[2rem] border border-white/10 bg-white/[0.05] p-3 shadow-panel backdrop-blur-2xl">
        <div class="flex items-center justify-between px-3 py-2">
            <div class="flex items-center gap-1.5" aria-hidden="true">
                <span class="h-2.5 w-2.5 rounded-full bg-rose-400/70"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-amber-300/70"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-mint-400/70"></span>
            </div>
            <span class="font-mono text-[0.62rem] uppercase tracking-[0.24em] text-slate-400">portfolio.app</span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-mint-500/15 px-2.5 py-1 text-[0.6rem] font-semibold uppercase tracking-[0.18em] text-mint-300">
                <span class="h-1.5 w-1.5 rounded-full bg-mint-400"></span>
                Live
            </span>
        </div>

        <div class="rounded-[1.5rem] border border-white/5 bg-ink-800/80 p-5">
            <!-- Headline figures -->
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-slate-500">Portfolio value</p>
                    <p class="mt-2 font-display text-3xl font-bold tracking-tight text-white">
                        $112,480<span class="text-slate-500">.32</span>
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center gap-1 rounded-full bg-mint-500/15 px-3 py-1 text-sm font-semibold text-mint-300">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75 10.5 9.75l3 3 6-6m0 0h-4.5m4.5 0v4.5" />
                        </svg>
                        +12.48%
                    </span>
                    <p class="mt-2 text-[0.68rem] text-slate-500">since inception</p>
                </div>
            </div>

            <!-- Equity curve -->
            <div class="sparkline mt-5 h-24 w-full">
                <svg viewBox="0 0 320 110" preserveAspectRatio="none" class="h-full w-full" aria-hidden="true">
                    <defs>
                        <linearGradient id="landing-spark-area" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#34d399" stop-opacity="0.45" />
                            <stop offset="100%" stop-color="#34d399" stop-opacity="0" />
                        </linearGradient>
                        <linearGradient id="landing-spark-line" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#6ee7b7" />
                            <stop offset="100%" stop-color="#22d3ee" />
                        </linearGradient>
                    </defs>
                    <path
                        data-spark-area
                        fill="url(#landing-spark-area)"
                        d="M0,88 C20,80 32,62 50,66 C68,70 80,42 102,46 C124,50 136,72 158,64 C180,56 194,30 216,34 C238,38 250,56 272,46 C294,36 306,22 320,18 L320,110 L0,110 Z"
                    />
                    <path
                        data-spark-line
                        fill="none"
                        stroke="url(#landing-spark-line)"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        d="M0,88 C20,80 32,62 50,66 C68,70 80,42 102,46 C124,50 136,72 158,64 C180,56 194,30 216,34 C238,38 250,56 272,46 C294,36 306,22 320,18"
                    />
                </svg>
            </div>

            <!-- Quick metrics -->
            <div class="mt-5 grid grid-cols-3 gap-3">
                @foreach ([
                    ['Cash', '$64,208'],
                    ['Positions', '6'],
                    ['Win rate', '68%'],
                ] as [$label, $value])
                    <div class="rounded-2xl border border-white/5 bg-white/[0.03] px-3 py-2.5 text-center">
                        <p class="text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-slate-500">{{ $label }}</p>
                        <p class="mt-1 font-display text-sm font-semibold text-white">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Live watchlist -->
            <div class="mt-5">
                <div class="flex items-center justify-between px-1 text-[0.62rem] font-semibold uppercase tracking-[0.2em] text-slate-500">
                    <span>Watchlist</span>
                    <span>Last / Change</span>
                </div>

                <div class="mt-3 space-y-2">
                    <template x-for="row in rows" :key="row.symbol">
                        <div class="flex items-center justify-between gap-3 rounded-2xl border border-white/5 bg-white/[0.03] px-3 py-2.5 transition duration-500">
                            <div class="flex min-w-0 items-center gap-3">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500/40 to-cyan-500/30 text-[0.68rem] font-bold text-white"
                                    x-text="row.symbol.slice(0, 3)"
                                ></span>
                                <span class="min-w-0">
                                    <span class="block truncate text-sm font-semibold text-white" x-text="row.name"></span>
                                    <span class="block truncate text-[0.68rem] text-slate-500" x-text="row.sector"></span>
                                </span>
                            </div>

                            <div class="shrink-0 text-right">
                                <p
                                    class="font-mono text-sm font-semibold transition-colors duration-500"
                                    :class="priceClass(row)"
                                    x-text="'$' + row.price.toFixed(2)"
                                ></p>
                                <p
                                    class="text-[0.68rem] font-semibold transition-colors duration-500"
                                    :class="row.change_percent >= 0 ? 'text-mint-400' : 'text-rose-400'"
                                    x-text="(row.change_percent >= 0 ? '+' : '') + row.change_percent.toFixed(2) + '%'"
                                ></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating notifications -->
    <div class="absolute -left-5 -top-8 hidden w-56 animate-float rounded-2xl border border-white/10 bg-ink-800/90 p-4 shadow-panel backdrop-blur-xl xl:block">
        <div class="flex items-center gap-2 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-mint-300">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
            Order filled
        </div>
        <p class="mt-2 text-sm font-semibold text-white">BUY 240 · NVDA</p>
        <p class="mt-1 font-mono text-xs text-slate-400">filled at $1,214.40 · $291,456</p>
    </div>

    <div class="absolute -bottom-16 -right-6 hidden w-60 animate-float-slow rounded-2xl border border-white/10 bg-ink-800/90 p-4 shadow-panel backdrop-blur-xl md:block">
        <div class="flex items-center justify-between">
            <span class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-400">Workspace rank</span>
            <span class="text-lg" aria-hidden="true">🥇</span>
        </div>
        <p class="mt-2 font-display text-base font-semibold text-white">Alpha Desk</p>
        <p class="mt-1 text-xs text-slate-400">
            <span class="font-semibold text-mint-300">+42.87%</span> · 118 trades
        </p>
    </div>
</div>
