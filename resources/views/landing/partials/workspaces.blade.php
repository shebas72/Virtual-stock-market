<!-- Team workspaces -->
<section id="workspaces" class="relative py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-14 lg:grid-cols-2">
            <div x-reveal>
                <span class="inline-flex items-center gap-2 rounded-full border border-brand-400/30 bg-brand-500/10 px-4 py-1.5 text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-brand-200">
                    Team workspaces
                </span>

                <h2 class="mt-5 font-display text-3xl font-bold leading-[1.15] tracking-tight text-white sm:text-4xl">
                    Run a league, a classroom or a friendly office war
                </h2>

                <p class="mt-4 text-base leading-relaxed text-slate-400">
                    Every workspace gets its own private market — members, roles and a leaderboard that only ranks your
                    people. Owners stay in control without a spreadsheet in sight.
                </p>

                <ul class="mt-8 space-y-4">
                    @foreach ([
                        ['Invite by link or email', 'Send a signed invitation that expires on its own and tracks who accepted.'],
                        ['Roles that mean something', 'Owners manage members and invites; traders get a private portfolio each.'],
                        ['Suspend in one click', 'Freeze a member without deleting their trade history.'],
                        ['Scoped leaderboards', 'Compare returns inside your workspace, or against the whole platform.'],
                    ] as [$title, $copy])
                        <li class="flex gap-4">
                            <span class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500/30 to-cyan-500/20 text-brand-200 ring-1 ring-inset ring-white/10">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-white">{{ $title }}</span>
                                <span class="mt-1 block text-sm leading-relaxed text-slate-400">{{ $copy }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div x-reveal style="transition-delay: 140ms" class="relative">
                <div class="pointer-events-none absolute -inset-6 -z-10 rounded-[2.5rem] bg-gradient-to-br from-brand-500/20 via-transparent to-mint-500/20 blur-3xl" aria-hidden="true"></div>

                <div class="rounded-[2rem] border border-white/10 bg-white/[0.04] p-5 shadow-panel backdrop-blur-2xl">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-cyan-500 font-display text-sm font-bold text-white">
                                AD
                            </span>
                            <div>
                                <p class="font-display text-sm font-semibold text-white">Alpha Desk</p>
                                <p class="text-xs text-slate-500">12 members · FinTech cohort</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-mint-500/15 px-3 py-1 text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-mint-300">
                            Active
                        </span>
                    </div>

                    <div class="mt-5 space-y-2">
                        @foreach ([
                            ['Aisha Rahman', 'Owner', 'Active', true],
                            ['Marco Vidal', 'Trader', 'Active', true],
                            ['Priya Nair', 'Trader', 'Active', true],
                            ['Sam Okafor', 'Trader', 'Suspended', false],
                        ] as [$name, $role, $status, $active])
                            <div class="flex items-center justify-between gap-3 rounded-2xl border border-white/5 bg-ink-800/60 px-4 py-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/[0.06] text-[0.65rem] font-bold text-slate-200">
                                        {{ Str::substr($name, 0, 1) }}
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block truncate text-sm font-medium text-white">{{ $name }}</span>
                                        <span class="block text-[0.68rem] text-slate-500">{{ $role }}</span>
                                    </span>
                                </div>
                                <span @class([
                                    'shrink-0 rounded-full px-2.5 py-1 text-[0.6rem] font-semibold uppercase tracking-[0.14em]',
                                    'bg-mint-500/15 text-mint-300' => $active,
                                    'bg-rose-500/15 text-rose-300' => ! $active,
                                ])>
                                    {{ $status }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex items-center justify-between gap-3 rounded-2xl border border-dashed border-white/15 bg-white/[0.02] px-4 py-3">
                        <span class="min-w-0">
                            <span class="block text-[0.62rem] font-semibold uppercase tracking-[0.16em] text-slate-500">Invitation link</span>
                            <span class="mt-1 block truncate font-mono text-xs text-slate-300">/invitations/8f21c4a7</span>
                        </span>
                        <span class="shrink-0 rounded-full bg-white/[0.06] px-3 py-1.5 text-[0.62rem] font-semibold uppercase tracking-[0.14em] text-slate-200">
                            Copy
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
