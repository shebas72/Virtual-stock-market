@props(['value', 'label', 'prefix' => '', 'suffix' => '', 'hint' => null])

<div
    x-reveal
    class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.03] px-5 py-6 text-center backdrop-blur-xl"
>
    <div class="pointer-events-none absolute inset-x-6 -top-16 h-24 rounded-full bg-brand-500/25 blur-3xl"></div>

    <div class="relative font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">
        @if ($prefix)<span class="text-brand-300">{{ $prefix }}</span>@endif<span x-counter="{{ $value }}">{{ number_format($value) }}</span>@if ($suffix)<span class="text-brand-300">{{ $suffix }}</span>@endif
    </div>

    <div class="relative mt-2 text-[0.7rem] font-semibold uppercase tracking-[0.18em] text-slate-400">
        {{ $label }}
    </div>

    @if ($hint)
        <div class="relative mt-1 text-xs text-slate-500">{{ $hint }}</div>
    @endif
</div>
