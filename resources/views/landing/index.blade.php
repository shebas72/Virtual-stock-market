<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Virtual Stock Market') }} — Trade the market without the risk</title>
        <meta name="description" content="A multiplayer virtual stock market with live quotes, $100,000 of simulated capital, real portfolio analytics, team workspaces and a global leaderboard.">
        <meta name="theme-color" content="#05060f">

        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="{{ config('app.name', 'Virtual Stock Market') }} — Trade the market without the risk">
        <meta property="og:description" content="Practise real trading decisions with simulated money, live market data and team competitions.">
        <link rel="canonical" href="{{ url('/') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|sora:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <script>
            // Marks the document as JS-capable so the scroll-reveal styles can
            // hide their targets before the bundle runs. If Alpine never boots
            // (blocked or failed asset) the state is dropped again so no text
            // can stay hidden.
            document.documentElement.classList.add('js');

            window.setTimeout(function () {
                if (! window.__alpineReady) {
                    document.documentElement.classList.remove('js');
                }
            }, 2500);
        </script>

        @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js'])
    </head>
    <body class="landing font-sans text-slate-300 antialiased selection:bg-brand-500/40 selection:text-white">
        <a
            href="#main"
            class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-brand-500 focus:px-5 focus:py-2 focus:text-sm focus:font-semibold focus:text-white"
        >
            Skip to content
        </a>

        <!-- x-data makes this the Alpine root for the page: Alpine only walks
             trees rooted at x-data, and the section reveals, animated counters
             and spotlight effects are driven by Alpine directives. -->
        <div x-data="{}" class="relative min-h-screen overflow-x-clip bg-ink-900">
            @include('landing.partials.nav')

            <main id="main">
                @include('landing.partials.hero')
                @include('landing.partials.ticker')
                @include('landing.partials.stats')
                @include('landing.partials.features')
                @include('landing.partials.how-it-works')
                @include('landing.partials.showcase')
                @include('landing.partials.leaderboard')
                @include('landing.partials.workspaces')
                @include('landing.partials.testimonials')
                @include('landing.partials.faq')
                @include('landing.partials.cta')
            </main>

            @include('landing.partials.footer')
        </div>
    </body>
</html>
