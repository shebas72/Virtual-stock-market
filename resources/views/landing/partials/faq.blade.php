<!-- FAQ -->
<section id="faq" class="relative py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
            <div x-reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-400/30 bg-brand-500/10 px-4 py-1.5 text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-brand-200">
                    Questions
                </span>

                <h2 class="mt-5 font-display text-3xl font-bold leading-[1.15] tracking-tight text-white sm:text-4xl">
                    The fine print, minus the fine print
                </h2>

                <p class="mt-4 text-base leading-relaxed text-slate-400">
                    Still curious about something? Create a free account and stress-test it yourself — there is nothing
                    to break.
                </p>

                <a
                    href="{{ route('register') }}"
                    class="group mt-8 inline-flex items-center gap-2 text-sm font-semibold text-brand-300 transition hover:text-brand-200"
                >
                    Start trading free
                    <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div x-reveal style="transition-delay: 120ms" class="rounded-[2rem] border border-white/10 bg-white/[0.03] px-6 py-2 backdrop-blur-xl sm:px-8">
                <x-landing.faq-item question="Is any real money involved?">
                    Never. Every account trades simulated cash, and positions are marked against live market prices so
                    the numbers still feel real. There is no deposit, no payout and no brokerage account.
                </x-landing.faq-item>

                <x-landing.faq-item question="Where do the prices come from?">
                    Quotes, daily OHLC values and volume are pulled from Finnhub. The scheduler refreshes every tracked
                    stock every five minutes, and you can trigger a refresh manually from the admin market screen.
                </x-landing.faq-item>

                <x-landing.faq-item question="Can I bring my class or my team?">
                    Yes. Create a workspace, then invite people by email or share an expiring invitation link. Every
                    member gets a private portfolio, and you can suspend access at any time without losing their history.
                </x-landing.faq-item>

                <x-landing.faq-item question="How does the leaderboard work?">
                    Portfolios are ranked by return percentage against the ${{ number_format($startingBalance) }} you
                    start with, so late joiners are never punished for missing an early rally.
                </x-landing.faq-item>

                <x-landing.faq-item question="What happens if I blow up my portfolio?">
                    An administrator can reset a single portfolio or the entire market back to its starting cash balance,
                    so a bad week never ends the lesson.
                </x-landing.faq-item>

                <x-landing.faq-item question="Do I need trading experience?">
                    Not at all. Start with the market overview, open a stock you actually recognise and place a small
                    order. The ticket tells you exactly what it will cost before you confirm.
                </x-landing.faq-item>
            </div>
        </div>
    </div>
</section>
