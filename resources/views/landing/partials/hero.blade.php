<section class="noise relative overflow-hidden pb-24 pt-32 sm:pt-40 lg:pb-32 lg:pt-44">
    <!-- Ambient background -->
    <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
        <div class="absolute inset-0 bg-grid-line bg-grid [mask-image:radial-gradient(ellipse_at_50%_0%,#000_10%,transparent_72%)] opacity-70"></div>
        <div class="absolute -left-32 -top-40 h-[42rem] w-[42rem] rounded-full bg-brand-600/25 blur-[150px] animate-float-slow"></div>
        <div class="absolute -right-24 top-10 h-[34rem] w-[34rem] rounded-full bg-cyan-500/20 blur-[140px] animate-float"></div>
        <div class="absolute bottom-[-12rem] left-1/3 h-[28rem] w-[28rem] rounded-full bg-mint-500/15 blur-[130px]"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-brand-400/40 to-transparent"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-12 lg:gap-10">
            <!-- Copy -->
            <div class="lg:col-span-6">
                <span
                    x-reveal
                    class="inline-flex items-center gap-2.5 rounded-full border border-white/10 bg-white/[0.05] px-4 py-1.5 text-xs font-medium text-slate-300 backdrop-blur-xl"
                >
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full rounded-full bg-mint-400 animate-pulse-ring"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-mint-400"></span>
                    </span>
                    Live market data · Simulated capital
                </span>

                <h1
                    x-reveal
                    style="transition-delay: 80ms"
                    class="mt-6 font-display text-4xl font-extrabold leading-[1.05] tracking-tight text-white sm:text-5xl lg:text-[3.75rem]"
                >
                    Master the market
                    <span class="mt-2 block text-gradient-brand">without the risk.</span>
                </h1>

                <p
                    x-reveal
                    style="transition-delay: 160ms"
                    class="mt-6 max-w-xl text-base leading-relaxed text-slate-400 sm:text-lg"
                >
                    Every trader starts with
                    <span class="font-semibold text-white">${{ number_format($startingBalance) }}</span>
                    in virtual cash, live quotes and the same analytics desks use. Build a strategy, invite your team
                    and climb the leaderboard — with zero real money on the line.
                </p>

                <div x-reveal style="transition-delay: 240ms" class="mt-9 flex flex-col gap-3 sm:flex-row sm:items-center">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-brand-500 to-cyan-500 px-7 py-3.5 text-sm font-semibold text-white shadow-glow transition duration-300 hover:-translate-y-0.5 hover:brightness-110"
                        >
                            Open my portfolio
                            <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @else
                        <a
                            href="{{ route('register') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-brand-500 to-cyan-500 px-7 py-3.5 text-sm font-semibold text-white shadow-glow transition duration-300 hover:-translate-y-0.5 hover:brightness-110"
                        >
                            Create free account
                            <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endauth

                    <a
                        href="#market"
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-white/15 bg-white/[0.04] px-7 py-3.5 text-sm font-semibold text-slate-100 backdrop-blur-xl transition duration-300 hover:-translate-y-0.5 hover:border-white/30 hover:bg-white/[0.08]"
                    >
                        <svg class="h-4 w-4 text-brand-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5 9 7.5l4 4 8-8M21 3.5h-5m5 0v5" />
                        </svg>
                        See today's movers
                    </a>
                </div>

                <ul x-reveal style="transition-delay: 320ms" class="mt-9 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-400">
                    @foreach ([
                        '$' . number_format($startingBalance) . ' starting capital',
                        'Live quotes every 5 minutes',
                        'Teams, invites & leaderboards',
                    ] as $point)
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0 text-mint-400" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Terminal preview -->
            <div class="lg:col-span-6">
                @include('landing.partials.hero-terminal')
            </div>
        </div>
    </div>
</section>
