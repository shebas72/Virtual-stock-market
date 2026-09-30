@php
    $medals = ['🥇', '🥈', '🥉'];
@endphp

<!-- Leaderboard -->
<section id="leaderboard" class="relative py-20 sm:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute left-1/4 top-10 h-[28rem] w-[28rem] rounded-full bg-amber-400/10 blur-[130px]"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-landing.section-heading
            eyebrow="Leaderboard"
            title="Bragging rights are the real currency"
            description="Return percentage decides everything. Track your workspace rivals trade by trade, or take on the whole platform."
        />

        <div class="mt-14 grid gap-5 lg:grid-cols-3">
            @foreach ($traders as $trader)
                <article
                    x-reveal
                    style="transition-delay: {{ $loop->index * 120 }}ms"
                    @class([
                        'relative overflow-hidden rounded-[1.75rem] border p-6 backdrop-blur-xl transition duration-500 hover:-translate-y-1.5',
                        'border-mint-400/30 bg-gradient-to-b from-mint-500/[0.14] to-white/[0.03] shadow-glow-mint' => $loop->first,
                        'border-white/10 bg-white/[0.035] shadow-panel-soft' => ! $loop->first,
                    ])
                >
                    <div class="flex items-center justify-between">
                        <span class="text-3xl" aria-hidden="true">{{ $medals[$loop->index] ?? '🏅' }}</span>
                        <span class="rounded-full border border-white/10 bg-white/[0.05] px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-slate-300">
                            Rank #{{ $trader['rank'] }}
                        </span>
                    </div>

                    <h3 class="mt-5 font-display text-lg font-semibold text-white">{{ $trader['name'] }}</h3>
                    <p class="mt-1 text-xs text-slate-400">{{ $trader['workspace'] }}</p>

                    <p class="mt-6 font-display text-3xl font-bold tracking-tight {{ $trader['return_percent'] >= 0 ? 'text-mint-300' : 'text-rose-400' }}">
                        {{ $trader['return_percent'] >= 0 ? '+' : '' }}{{ number_format($trader['return_percent'], 2) }}%
                    </p>

                    <dl class="mt-5 grid grid-cols-2 gap-3 border-t border-white/10 pt-4 text-sm">
                        <div>
                            <dt class="text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-slate-500">Portfolio</dt>
                            <dd class="mt-1 font-mono font-semibold text-white">${{ number_format($trader['total_value'], 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-slate-500">Trades</dt>
                            <dd class="mt-1 font-mono font-semibold text-white">{{ number_format($trader['trades']) }}</dd>
                        </div>
                    </dl>
                </article>
            @endforeach
        </div>

        <p x-reveal class="mt-8 text-center text-sm text-slate-400">
            Rankings refresh with every trade.
            <a href="{{ route('register') }}" class="font-semibold text-brand-300 underline decoration-brand-400/40 underline-offset-4 transition hover:text-brand-200">
                Claim your seat on the board
            </a>
        </p>
    </div>
</section>
