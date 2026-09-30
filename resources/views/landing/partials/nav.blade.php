<header
    x-data="{ scrolled: false, open: false }"
    x-on:scroll.window="scrolled = window.scrollY > 24"
    :class="scrolled ? 'border-white/10 bg-ink-900/85 shadow-panel-soft backdrop-blur-xl' : 'border-transparent'"
    class="fixed inset-x-0 top-0 z-50 border-b transition duration-500"
>
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8" aria-label="Primary">
        <!-- Brand -->
        <a href="{{ url('/') }}" class="group flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-cyan-500 text-white shadow-glow transition duration-500 group-hover:scale-105">
                <x-application-logo class="h-6 w-6 fill-current" />
            </span>
            <span class="flex flex-col leading-none">
                <span class="font-display text-sm font-bold tracking-tight text-white">
                    {{ config('app.name', 'Virtual Stock Market') }}
                </span>
                <span class="mt-1 text-[0.6rem] font-semibold uppercase tracking-[0.24em] text-brand-200/80">
                    Trading simulator
                </span>
            </span>
        </a>

        <!-- Desktop links -->
        <div class="hidden items-center gap-1 lg:flex">
            @foreach ([
                ['#features', 'Features'],
                ['#how-it-works', 'How it works'],
                ['#market', 'Markets'],
                ['#leaderboard', 'Leaderboard'],
                ['#pricing', 'Pricing'],
                ['#faq', 'FAQ'],
            ] as [$href, $label])
                <a
                    href="{{ $href }}"
                    class="rounded-full px-4 py-2 text-sm font-medium text-slate-300 transition duration-300 hover:bg-white/[0.06] hover:text-white"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Desktop actions -->
        <div class="hidden items-center gap-3 lg:flex">
            @auth
                <a
                    href="{{ url('/dashboard') }}"
                    class="group inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-brand-500 to-cyan-500 px-5 py-2.5 text-sm font-semibold text-white shadow-glow transition duration-300 hover:brightness-110"
                >
                    Go to dashboard
                    <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            @else
                <a
                    href="{{ route('login') }}"
                    class="rounded-full px-4 py-2 text-sm font-medium text-slate-200 transition duration-300 hover:bg-white/[0.06] hover:text-white"
                >
                    Log in
                </a>
                <a
                    href="{{ route('register') }}"
                    class="group inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-brand-500 to-cyan-500 px-5 py-2.5 text-sm font-semibold text-white shadow-glow transition duration-300 hover:brightness-110"
                >
                    Start trading free
                    <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            @endauth
        </div>

        <!-- Mobile toggle -->
        <button
            type="button"
            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/15 bg-white/[0.04] text-slate-200 transition duration-300 hover:bg-white/[0.08] lg:hidden"
            @click="open = ! open"
            :aria-expanded="open ? 'true' : 'false'"
            aria-label="Toggle navigation"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path x-show="! open" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                <path x-show="open" x-cloak stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
            </svg>
        </button>
    </nav>

    <!-- Mobile menu -->
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition duration-200 ease-in"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="border-t border-white/10 bg-ink-900/95 px-4 pb-6 pt-4 backdrop-blur-xl lg:hidden"
    >
        <div class="flex flex-col gap-1">
            @foreach ([
                ['#features', 'Features'],
                ['#how-it-works', 'How it works'],
                ['#market', 'Markets'],
                ['#leaderboard', 'Leaderboard'],
                ['#pricing', 'Pricing'],
                ['#faq', 'FAQ'],
            ] as [$href, $label])
                <a
                    href="{{ $href }}"
                    @click="open = false"
                    class="rounded-2xl px-4 py-3 text-sm font-medium text-slate-200 transition duration-300 hover:bg-white/[0.06] hover:text-white"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="mt-4 flex flex-col gap-2 border-t border-white/10 pt-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="rounded-2xl bg-gradient-to-r from-brand-500 to-cyan-500 px-4 py-3 text-center text-sm font-semibold text-white">
                    Go to dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="rounded-2xl border border-white/15 px-4 py-3 text-center text-sm font-semibold text-slate-100">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="rounded-2xl bg-gradient-to-r from-brand-500 to-cyan-500 px-4 py-3 text-center text-sm font-semibold text-white">
                    Start trading free
                </a>
            @endauth
        </div>
    </div>
</header>
