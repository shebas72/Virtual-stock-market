<!-- How it works -->
<section id="how-it-works" class="relative py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-landing.section-heading
            eyebrow="How it works"
            title="From sign-up to strategy in three moves"
            description="No brokerage, no paperwork and no market-hours anxiety — just the decisions that actually teach you to trade."
        />

        <div class="relative mt-14">
            <div class="pointer-events-none absolute left-0 right-0 top-16 hidden h-px bg-gradient-to-r from-transparent via-brand-400/40 to-transparent lg:block" aria-hidden="true"></div>

            <ol class="grid gap-5 lg:grid-cols-3">
                @foreach ([
                    [
                        'step' => '01',
                        'title' => 'Create your account',
                        'copy' => 'Sign up in seconds. We open a portfolio with $' . number_format($startingBalance) . ' of virtual cash and stream live quotes straight into it.',
                    ],
                    [
                        'step' => '02',
                        'title' => 'Research and trade',
                        'copy' => 'Screen the market, open any stock for charts and key stats, then place buy and sell orders with instant cost validation.',
                    ],
                    [
                        'step' => '03',
                        'title' => 'Compete and review',
                        'copy' => 'Watch P&L against your team, climb the workspace leaderboard and audit every trade you have ever made.',
                    ],
                ] as $index => $item)
                    <li
                        x-reveal
                        style="transition-delay: {{ $index * 120 }}ms"
                        class="relative rounded-3xl border border-white/10 bg-white/[0.035] p-6 backdrop-blur-xl transition duration-500 hover:-translate-y-1.5 hover:border-white/20 hover:bg-white/[0.06]"
                    >
                        <span class="relative z-10 flex h-12 w-12 items-center justify-center rounded-2xl bg-ink-900 font-display text-sm font-bold text-brand-200 ring-1 ring-inset ring-white/15">
                            {{ $item['step'] }}
                        </span>
                        <h3 class="mt-5 font-display text-lg font-semibold text-white">{{ $item['title'] }}</h3>
                        <p class="mt-2.5 text-sm leading-relaxed text-slate-400">{{ $item['copy'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>

        @include('landing.partials.order-ticket')
    </div>
</section>
