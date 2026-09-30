<!-- Features -->
<section id="features" class="relative py-20 sm:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute left-1/2 top-0 h-[30rem] w-[52rem] -translate-x-1/2 rounded-full bg-brand-600/10 blur-[130px]"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-landing.section-heading
            eyebrow="Everything a desk needs"
            title="A trading floor in your browser"
            description="Built for classrooms, clubs and friendly rivalries: real market data, serious analytics and the team controls to keep everyone honest."
        />

        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <x-landing.feature-card
                title="Live market quotes"
                description="Prices, daily OHLC and volume stream in from Finnhub on a five-minute scheduler, so your decisions track the real tape."
            >
                <x-slot:icon>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 17.25 8.25 12l3.75 3.75L21 6.75M21 6.75h-5.25M21 6.75V12" />
                    </svg>
                </x-slot:icon>
            </x-landing.feature-card>

            <x-landing.feature-card
                title="Portfolio analytics"
                description="Cost basis, unrealised P&amp;L, sector allocation and win rate are recalculated for every position the moment prices move."
            >
                <x-slot:icon>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7.5 15.75V10.5m4.5 5.25V6.75m4.5 9V9" />
                    </svg>
                </x-slot:icon>
            </x-landing.feature-card>

            <x-landing.feature-card
                title="Order tickets with guardrails"
                description="Buy and sell with live cost previews, buying-power checks and share limits, so nobody can oversell a position."
            >
                <x-slot:icon>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </x-slot:icon>
            </x-landing.feature-card>

            <x-landing.feature-card
                title="Team workspaces"
                description="Spin up private cohorts, invite by link or email, promote members and suspend access in a click — all scoped to your workspace."
            >
                <x-slot:icon>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9 9 0 0 0 3.74-.64 3 3 0 0 0-4.68-2.9M18 18.72V19.5a9.2 9.2 0 0 1-.35 2.5A9 9 0 0 1 12 23.25M18 18.72a5.2 5.2 0 0 0-.25-1.62M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM9 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-3 3a9 9 0 0 0-6 2.24v1.01c0 .87.75 1.5 1.62 1.5H9" />
                    </svg>
                </x-slot:icon>
            </x-landing.feature-card>

            <x-landing.feature-card
                title="Leaderboards & rivalry"
                description="Rank portfolios by return percentage across your workspace or the whole platform, with medals for the top three traders."
            >
                <x-slot:icon>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.62-.5-1.125-1.125-1.125H14.25m-4.5 4.5v-3.375c0-.62.5-1.125 1.125-1.125H11.25m3 0V6.375c0-.62.5-1.125 1.125-1.125h2.25c.62 0 1.125.5 1.125 1.125v8.25M11.25 10.125V4.5c0-.62-.5-1.125-1.125-1.125h-2.25c-.62 0-1.125.5-1.125 1.125v9.75m4.5-4.125h-4.5" />
                    </svg>
                </x-slot:icon>
            </x-landing.feature-card>

            <x-landing.feature-card
                title="Complete audit trail"
                description="Every trade, note and timestamp is stored and searchable, so you can review the reasoning behind each decision long after the fact."
            >
                <x-slot:icon>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.97 8.97 0 0 0 6 3.75c-1.05 0-2.06.18-3 .51v15.6c.94-.33 1.95-.51 3-.51 2.3 0 4.34.92 6 2.4m0-21.6c.99-.37 2.05-.6 3.15-.6 1.35 0 2.63.31 3.85.87v15.6a10 10 0 0 0-3.85-.75c-1.1 0-2.16.23-3.15.6M12 6.042v18" />
                    </svg>
                </x-slot:icon>
            </x-landing.feature-card>
        </div>
    </div>
</section>
