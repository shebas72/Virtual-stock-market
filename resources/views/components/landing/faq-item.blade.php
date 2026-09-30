@props(['question'])

<div class="border-b border-white/10" x-data="{ open: false }">
    <button
        type="button"
        class="flex w-full items-center justify-between gap-6 py-5 text-left"
        @click="open = ! open"
        :aria-expanded="open ? 'true' : 'false'"
    >
        <span class="font-display text-base font-medium text-white sm:text-lg">{{ $question }}</span>

        <span
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/15 bg-white/5 text-slate-300 transition duration-300"
            :class="open ? 'rotate-45 border-brand-400/60 text-brand-200' : ''"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" d="M12 5v14M5 12h14" />
            </svg>
        </span>
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition duration-200 ease-in"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="pb-6 pr-10 text-sm leading-relaxed text-slate-400"
    >
        {{ $slot }}
    </div>
</div>
