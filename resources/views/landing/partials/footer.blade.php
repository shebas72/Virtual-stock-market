<footer class="relative border-t border-white/10 bg-ink-900/80">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
            <!-- Brand -->
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-cyan-500 text-white shadow-glow">
                        <x-application-logo class="h-6 w-6 fill-current" />
                    </span>
                    <span class="font-display text-sm font-bold tracking-tight text-white">
                        {{ config('app.name', 'Virtual Stock Market') }}
                    </span>
                </a>

                <p class="mt-4 max-w-sm text-sm leading-relaxed text-slate-400">
                    A multiplayer trading simulator for classrooms, clubs and teams. Real market data, simulated
                    capital, zero risk.
                </p>

                <span class="mt-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-3.5 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-slate-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-mint-400"></span>
                    Demo environment
                </span>
            </div>

            <!-- Product -->
            <div>
                <h3 class="text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-slate-500">Product</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    @foreach ([
                        ['#features', 'Features'],
                        ['#how-it-works', 'How it works'],
                        ['#market', 'Market tape'],
                        ['#leaderboard', 'Leaderboard'],
                        ['#workspaces', 'Workspaces'],
                    ] as [$href, $label])
                        <li>
                            <a href="{{ $href }}" class="text-slate-400 transition duration-300 hover:text-white">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Platform -->
            <div>
                <h3 class="text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-slate-500">Platform</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    @foreach ([
                        [route('stocks.index'), 'Stocks'],
                        [route('portfolio.index'), 'Portfolio'],
                        [route('transactions.index'), 'Transactions'],
                        [route('market'), 'Market overview'],
                        [route('leaderboard'), 'Leaderboard'],
                    ] as [$href, $label])
                        <li>
                            <a href="{{ $href }}" class="text-slate-400 transition duration-300 hover:text-white">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Get started -->
            <div>
                <h3 class="text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-slate-500">Get started</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    @auth
                        <li>
                            <a href="{{ url('/dashboard') }}" class="text-slate-400 transition duration-300 hover:text-white">Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}" class="text-slate-400 transition duration-300 hover:text-white">Profile settings</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('register') }}" class="text-slate-400 transition duration-300 hover:text-white">Create an account</a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" class="text-slate-400 transition duration-300 hover:text-white">Log in</a>
                        </li>
                        <li>
                            <a href="{{ route('password.request') }}" class="text-slate-400 transition duration-300 hover:text-white">Reset a password</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 text-xs text-slate-500 sm:flex-row">
            <p>&copy; {{ now()->year }} {{ config('app.name', 'Virtual Stock Market') }}. Simulated trading only.</p>
            <p class="text-center sm:text-right">
                Built with Laravel · Tailwind CSS · Alpine.js · Quotes by Finnhub
            </p>
        </div>
    </div>
</footer>
