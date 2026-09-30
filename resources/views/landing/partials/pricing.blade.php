<!-- Plans, pricing and the free trial -->
@php
    // Seat tiers feed the Alpine seat estimator; the grid widens with the
    // number of plans an administrator has published.
    $planTiers = array_map(
        fn (array $plan): array => ['name' => $plan['name'], 'seats' => $plan['seats']],
        $plans,
    );
    $maxSeats = $plans === [] ? 0 : max(array_column($plans, 'seats'));
    $estimatorStart = max(1, min(12, $maxSeats));
    $planGrid = match (true) {
        count($plans) === 1 => 'sm:grid-cols-1',
        count($plans) === 2 => 'sm:grid-cols-2',
        default => 'sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<section id="pricing" class="relative py-20 sm:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute left-1/2 top-8 h-[32rem] w-[56rem] -translate-x-1/2 rounded-full bg-mint-500/10 blur-[140px]"></div>
        <div class="absolute bottom-0 right-8 h-72 w-72 rounded-full bg-brand-600/15 blur-[120px]"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-landing.section-heading
            eyebrow="Plans & pricing"
            title="Free to start, priced by seats — never by features"
            description="Every plan unlocks the whole trading platform: live quotes, portfolio analytics, workspaces and leaderboards. The only thing that changes is how many traders your workspace can hold."
        />

        <!-- Free trial banner -->
        <div
            x-reveal
            class="noise relative mx-auto mt-12 max-w-5xl overflow-hidden rounded-[2rem] border border-mint-400/25 bg-gradient-to-br from-mint-600/20 via-ink-800/90 to-brand-600/20 p-6 shadow-panel backdrop-blur-xl sm:p-8"
        >
            <div class="grid items-center gap-8 lg:grid-cols-[1.05fr_1fr]">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-mint-400/30 bg-mint-500/15 px-4 py-1.5 text-[0.68rem] font-semibold uppercase tracking-[0.2em] text-mint-200">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                        </svg>
                        {{ $trialDays }}-day free trial
                    </span>

                    <h3 class="mt-5 font-display text-2xl font-bold leading-tight tracking-tight text-white sm:text-3xl">
                        Every workspace starts unlocked — no card needed
                    </h3>

                    <p class="mt-4 text-sm leading-relaxed text-slate-300 sm:text-base">
                        Creating an account opens a workspace in trial, so the complete platform is yours for
                        {{ $trialDays }} days before any billing decision is made. Sign up today and that runs through
                        <span class="font-semibold text-mint-200">{{ $trialEndsOn }}</span>.
                    </p>

                    <ul class="mt-6 flex flex-wrap gap-2">
                        @foreach (['No card required', 'No deposits, no payouts', 'Full platform, not a demo', 'Nothing charges automatically'] as $perk)
                            <li class="rounded-full border border-white/10 bg-white/[0.06] px-3.5 py-1.5 text-xs font-medium text-slate-200">
                                {{ $perk }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <ol class="relative space-y-4">
                    @foreach ([
                        ['Create your account', 'Sign up and land straight in your portfolio with $' . number_format($startingBalance) . ' of simulated cash.'],
                        ['The trial starts itself', 'Your workspace stays active for ' . $trialDays . ' days with every feature switched on.'],
                        ['Choose a plan when ready', 'The owner picks a plan from the subscription screen — seats are the only limit.'],
                    ] as $index => $step)
                        <li class="flex gap-4 rounded-2xl border border-white/10 bg-ink-900/50 px-4 py-3.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-mint-500/30 to-brand-500/25 font-display text-xs font-bold text-mint-100 ring-1 ring-inset ring-white/10">
                                {{ $index + 1 }}
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-white">{{ $step[0] }}</span>
                                <span class="mt-1 block text-xs leading-relaxed text-slate-400">{{ $step[1] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <!-- The estimator and the cards share one Alpine scope so the estimator
             can flag which tier fits the visitor's headcount. -->
        <div x-data="planFinder(@js($planTiers), {{ $estimatorStart }})">
            <div
                x-reveal
                class="mx-auto mt-10 flex max-w-4xl flex-col gap-5 rounded-[2rem] border border-white/10 bg-white/[0.03] px-6 py-6 backdrop-blur-xl sm:px-8"
            >
                <div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <label for="seat-estimator" class="text-sm font-medium text-slate-300">
                            How many traders are you bringing?
                        </label>
                        <span class="rounded-full border border-white/10 bg-white/[0.06] px-3 py-1 font-display text-xs font-semibold text-white">
                            <span x-text="traders"></span>
                            <span class="text-slate-400" x-text="traderLabel"></span>
                        </span>
                    </div>

                    <input
                        id="seat-estimator"
                        type="range"
                        min="1"
                        max="{{ max($maxSeats, 1) }}"
                        step="1"
                        x-model.number="traders"
                        class="plan-slider mt-4 w-full"
                        aria-describedby="seat-estimator-result"
                    />

                    <div class="mt-2 flex justify-between font-mono text-[0.65rem] uppercase tracking-[0.16em] text-slate-500">
                        <span>1</span>
                        <span>{{ $maxSeats }} seats max</span>
                    </div>
                </div>

                <p id="seat-estimator-result" class="text-sm text-slate-400">
                    Best fit:
                    <span class="font-display text-sm font-semibold text-mint-300" x-text="matchName"></span>
                    <span class="text-slate-500">· holds up to <span x-text="matchSeats"></span> traders</span>
                </p>
            </div>

            <div class="mt-12 grid gap-6 {{ $planGrid }}">
                @foreach ($plans as $plan)
                    @php
                        $priceLabel = $plan['price'] > 0 ? '$'.number_format($plan['price'], 2) : 'Free';
                    @endphp
                    <article
                        x-spotlight
                        x-reveal
                        style="transition-delay: {{ $loop->index * 110 }}ms"
                        @class([
                            'relative flex h-full flex-col rounded-[2rem] border p-7 backdrop-blur-xl transition duration-500',
                            'border-white/10 bg-white/[0.035] hover:-translate-y-1.5 hover:border-white/20 hover:bg-white/[0.06]' => ! $plan['popular'],
                            'border-brand-400/40 bg-gradient-to-b from-brand-600/30 via-ink-800/85 to-ink-900/70 shadow-glow hover:-translate-y-1.5' => $plan['popular'],
                        ])
                    >
                        @if ($plan['popular'])
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-brand-500 to-cyan-400 px-4 py-1 text-[0.62rem] font-bold uppercase tracking-[0.2em] text-white shadow-glow">
                                Most popular
                            </span>
                        @endif

                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="font-display text-lg font-semibold text-white">{{ $plan['name'] }}</h3>
                                <p class="mt-2 max-w-xs text-sm leading-relaxed text-slate-400">{{ $plan['tagline'] }}</p>
                            </div>

                            @if ($plan['is_default'] ?? false)
                                <span class="shrink-0 rounded-full bg-mint-500/15 px-3 py-1 text-[0.6rem] font-semibold uppercase tracking-[0.14em] text-mint-300">
                                    Default
                                </span>
                            @endif
                        </div>

                        <div class="mt-7 flex items-end gap-2.5">
                            @if ($plan['list_price'] > $plan['price'] && $plan['price'] > 0)
                                <span class="mb-2 text-sm text-slate-500 line-through">${{ number_format($plan['list_price'], 2) }}</span>
                            @endif

                            <span class="font-display text-4xl font-extrabold tracking-tight text-white">{{ $priceLabel }}</span>
                            <span class="mb-1.5 text-sm text-slate-400">
                                {{ $plan['price'] > 0 ? '/ '.$plan['term'] : 'to start' }}
                            </span>
                        </div>

                        <p class="mt-2.5 text-xs leading-relaxed text-slate-500">
                            <span class="font-semibold text-mint-300">{{ $trialDays }}-day trial</span>
                            included ·
                            {{ $plan['price'] > 0 ? $priceLabel.' every '.$plan['term'].' once the trial ends' : 'no card, no deposit, nothing to cancel' }}
                        </p>

                        <div class="mt-6 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.04] px-4 py-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500/30 to-cyan-500/20 text-brand-200 ring-1 ring-inset ring-white/10">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.9" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.13v-.63a3.37 3.37 0 0 0-3.38-3.37H6.37A3.37 3.37 0 0 0 3 18.5v.63M12 3.75a3.38 3.38 0 1 1 0 6.75 3.38 3.38 0 0 1 0-6.75Zm6.75 4.5a2.62 2.62 0 1 1 0 5.25 2.62 2.62 0 0 1 0-5.25Z" />
                                </svg>
                            </span>
                            <span>
                                <span class="block font-display text-base font-bold text-white">{{ $plan['seats'] }}</span>
                                <span class="block text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-slate-500">
                                    Trader seats included
                                </span>
                            </span>
                        </div>

                        <ul class="mt-6 space-y-3">
                            @foreach ($plan['features'] as $feature)
                                <li class="flex gap-3 text-sm leading-relaxed text-slate-300">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-mint-400/80" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-auto pt-8">
                            @auth
                                <a
                                    href="{{ route('subscription.show') }}"
                                    @class([
                                        'block w-full rounded-full px-6 py-3 text-center text-sm font-semibold transition duration-300',
                                        'bg-gradient-to-r from-brand-500 to-cyan-500 text-white hover:brightness-110' => $plan['popular'],
                                        'border border-white/20 bg-white/[0.06] text-white hover:border-white/40 hover:bg-white/[0.12]' => ! $plan['popular'],
                                    ])
                                >
                                    Manage workspace plan
                                </a>
                            @else
                                <a
                                    href="{{ route('register') }}"
                                    @class([
                                        'block w-full rounded-full px-6 py-3 text-center text-sm font-semibold transition duration-300',
                                        'bg-gradient-to-r from-brand-500 to-cyan-500 text-white hover:brightness-110' => $plan['popular'],
                                        'border border-white/20 bg-white/[0.06] text-white hover:border-white/40 hover:bg-white/[0.12]' => ! $plan['popular'],
                                    ])
                                >
                                    Start the {{ $trialDays }}-day free trial
                                </a>
                            @endauth

                            <p class="mt-4 text-center text-[0.68rem] leading-relaxed text-slate-500">
                                {{ $plan['term'] }} term · seats are the only difference between plans
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <!-- Subscription details, plan by plan -->
        @php
            // Closures keep every row truthful: the values come from the plan
            // rows the controller assembled, never from a hard-coded ladder.
            $detailRows = [
                'Trader seats' => fn (array $plan): string => (string) $plan['seats'],
                'Billing term' => fn (array $plan): string => $plan['term'],
                'Standard price' => fn (array $plan): string => $plan['list_price'] > 0 ? '$'.number_format($plan['list_price'], 2) : 'Free',
                'Price you pay' => fn (array $plan): string => $plan['price'] > 0
                    ? '$'.number_format($plan['price'], 2).' every '.$plan['term']
                    : 'Free',
                'Free trial' => fn (array $plan): string => $trialDays.' days',
                'Payment card required' => fn (array $plan): string => 'No',
                'Seat limit reached' => fn (array $plan): string => 'New members and invitations pause',
                'When the trial ends' => fn (array $plan): string => 'Owner picks a plan to stay open',
            ];
        @endphp

        <div
            x-reveal
            class="mt-16 overflow-hidden rounded-[2rem] border border-white/10 bg-white/[0.03] backdrop-blur-xl"
        >
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-6 py-5 sm:px-8">
                <h3 class="font-display text-base font-semibold text-white">The subscription details, line by line</h3>
                <span class="text-xs text-slate-500">Same platform on every tier — only the seat allowance changes</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[44rem] border-collapse text-left text-sm">
                    <caption class="sr-only">Side-by-side subscription details for every published plan</caption>
                    <thead>
                        <tr class="border-b border-white/10 bg-white/[0.04]">
                            <th scope="col" class="px-6 py-4 text-[0.66rem] font-semibold uppercase tracking-[0.18em] text-slate-500 sm:px-8">
                                Plan detail
                            </th>
                            @foreach ($plans as $plan)
                                <th scope="col" class="px-6 py-4 font-display text-sm font-semibold text-white">
                                    {{ $plan['name'] }}
                                    @if ($plan['popular'])
                                        <span class="ml-2 rounded-full bg-brand-500/20 px-2 py-0.5 text-[0.58rem] font-semibold uppercase tracking-[0.14em] text-brand-200">Popular</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailRows as $label => $value)
                            <tr class="border-b border-white/5 last:border-b-0">
                                <th scope="row" class="px-6 py-4 text-left font-medium text-slate-400 sm:px-8">{{ $label }}</th>
                                @foreach ($plans as $plan)
                                    <td @class([
                                        'px-6 py-4 text-white',
                                        'bg-brand-500/[0.07]' => $plan['popular'],
                                    ])>{{ $value($plan) }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Included with every plan -->
        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['Live market data', 'Quotes, daily OHLC and volume from Finnhub, refreshed every '.$refreshMinutes.' minutes.', 'M3 17.25 8.25 12l3.75 3.75L21 6.75M21 6.75h-5.25M21 6.75V12'],
                ['Portfolio analytics', 'Cost basis, unrealised P&L, allocation and win rate recalculate as prices move.', 'M3 3v18h18M7.5 15.75V10.5m4.5 5.25V6.75m4.5 9V9'],
                ['Workspaces & leagues', 'Private cohorts, expiring invitations, roles and scoped leaderboards.', 'M18 18.72a9 9 0 0 0 3.74-.64 3 3 0 0 0-4.68-2.9M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM9 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-3 3a9 9 0 0 0-6 2.24v1.01c0 .87.75 1.5 1.62 1.5H9'],
                ['Audit trail', 'Every order, note and timestamp stays searchable after the lesson ends.', 'M12 6.042A8.97 8.97 0 0 0 6 3.75c-1.05 0-2.06.18-3 .51v15.6c.94-.33 1.95-.51 3-.51 2.3 0 4.34.92 6 2.4m0-21.6c1.1 0 2.16.23 3.15.6v18.75a10 10 0 0 0-3.15-.6M12 6.042v18'],
            ] as $index => $item)
                <div
                    x-reveal
                    style="transition-delay: {{ $index * 90 }}ms"
                    class="rounded-3xl border border-white/10 bg-white/[0.035] p-5 backdrop-blur-xl"
                >
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-mint-500/25 to-brand-500/20 text-mint-200 ring-1 ring-inset ring-white/10">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item[2] }}" />
                        </svg>
                    </span>
                    <h4 class="mt-4 font-display text-sm font-semibold text-white">{{ $item[0] }}</h4>
                    <p class="mt-2 text-xs leading-relaxed text-slate-400">{{ $item[1] }}</p>
                </div>
            @endforeach
        </div>

        <p x-reveal class="mx-auto mt-10 max-w-3xl text-center text-xs leading-relaxed text-slate-500">
            Plans and trial length are set by the platform administrator and billing is settled outside this
            application — the app never stores a card and nothing is charged on its own. When a trial or a term lapses
            the workspace pauses at the subscription screen until its owner chooses a plan; portfolios and trade
            history are never deleted.
        </p>
    </div>
</section>
