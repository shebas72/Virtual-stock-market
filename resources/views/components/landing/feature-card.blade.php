@props(['title', 'description'])

<article
    x-spotlight
    x-reveal
    class="group relative flex h-full flex-col rounded-3xl border border-white/10 bg-white/[0.035] p-6 shadow-panel-soft backdrop-blur-xl transition duration-500 hover:-translate-y-1.5 hover:border-white/20 hover:bg-white/[0.06]"
>
    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500/30 via-brand-500/10 to-cyan-500/20 text-brand-200 ring-1 ring-inset ring-white/10 transition duration-500 group-hover:scale-110 group-hover:text-white">
        {{ $icon }}
    </span>

    <h3 class="mt-5 font-display text-lg font-semibold text-white">
        {{ $title }}
    </h3>

    <p class="mt-2.5 text-sm leading-relaxed text-slate-400">
        {{ $description }}
    </p>

    {{ $slot }}
</article>
