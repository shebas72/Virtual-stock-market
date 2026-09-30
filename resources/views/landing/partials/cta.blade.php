<!-- Closing call to action -->
<section class="relative pb-24 pt-8 sm:pb-32">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div
            x-reveal
            class="noise relative overflow-hidden rounded-[2.5rem] border border-white/10 bg-gradient-to-br from-brand-600/35 via-ink-800 to-cyan-600/25 px-6 py-16 text-center shadow-panel sm:px-16"
        >
            <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
                <div class="absolute inset-0 bg-grid-line bg-grid opacity-40 [mask-image:radial-gradient(ellipse_at_center,#000,transparent_70%)]"></div>
                <div class="absolute -left-16 -top-24 h-72 w-72 rounded-full bg-brand-500/30 blur-[120px]"></div>
                <div class="absolute -bottom-24 -right-10 h-72 w-72 rounded-full bg-mint-500/25 blur-[120px]"></div>
            </div>

            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.06] px-4 py-1.5 text-[0.68rem] font-semibold uppercase tracking-[0.2em] text-white/80">
                Free · No card required
            </span>

            <h2 class="mx-auto mt-6 max-w-3xl font-display text-3xl font-extrabold leading-[1.1] tracking-tight text-white sm:text-4xl lg:text-5xl">
                Your first ${{ number_format($startingBalance) }} is already waiting
            </h2>

            <p class="mx-auto mt-5 max-w-2xl text-base leading-relaxed text-slate-200/80 sm:text-lg">
                Create an account, pick a stock you actually believe in and let the market tell you whether you were
                right. Zero risk, all the lessons.
            </p>

            <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                        class="group inline-flex w-full items-center justify-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-semibold text-ink-900 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100 sm:w-auto"
                    >
                        Open my dashboard
                        <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                @else
                    <a
                        href="{{ route('register') }}"
                        class="group inline-flex w-full items-center justify-center gap-2 rounded-full bg-white px-7 py-3.5 text-sm font-semibold text-ink-900 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-100 sm:w-auto"
                    >
                        Create free account
                        <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>

                    <a
                        href="{{ route('login') }}"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-white/25 bg-white/[0.06] px-7 py-3.5 text-sm font-semibold text-white backdrop-blur-xl transition duration-300 hover:-translate-y-0.5 hover:border-white/40 hover:bg-white/[0.12] sm:w-auto"
                    >
                        I already have an account
                    </a>
                @endauth
            </div>

            <p class="mt-6 text-xs text-slate-300/70">
                Simulated trading only · Live market data courtesy of Finnhub
            </p>
        </div>
    </div>
</section>
