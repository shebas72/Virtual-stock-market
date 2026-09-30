@props([
    'eyebrow' => null,
    'title' => null,
    'description' => null,
    'align' => 'center',
])

<div
    x-reveal
    @class([
        'mx-auto max-w-3xl',
        'text-center' => $align === 'center',
        'text-left' => $align === 'left',
    ])
>
    @if ($eyebrow)
        <span class="inline-flex items-center gap-2 rounded-full border border-brand-400/30 bg-brand-500/10 px-4 py-1.5 text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-brand-200">
            {{ $eyebrow }}
        </span>
    @endif

    @if ($title)
        <h2 class="mt-5 font-display text-3xl font-bold leading-[1.15] tracking-tight text-white sm:text-4xl lg:text-[2.6rem]">
            {{ $title }}
        </h2>
    @endif

    @if ($description)
        <p class="mt-4 text-base leading-relaxed text-slate-400 sm:text-lg">
            {{ $description }}
        </p>
    @endif

    {{ $slot }}
</div>
