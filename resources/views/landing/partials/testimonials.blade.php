<!-- Testimonials -->
<section class="relative py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-landing.section-heading
            eyebrow="Traders talking"
            title="The cheapest way to make expensive mistakes"
            description="Lecturers, team leads and self-taught traders use the simulator to build instincts before real money is involved."
        />

        <div class="mt-14 grid gap-5 lg:grid-cols-3">
            @foreach ([
                [
                    'quote' => 'We run the whole finance elective on it. Students finally feel what a drawdown does to their decision-making — without anyone losing a cent.',
                    'name' => 'Dana Whitfield',
                    'role' => 'Finance lecturer · Northgate College',
                ],
                [
                    'quote' => 'Our Monday stand-up starts with the leaderboard. It is genuinely the most competitive thing we do all week.',
                    'name' => 'Marcus Lee',
                    'role' => 'Revenue lead · Brightpath',
                ],
                [
                    'quote' => 'I learned to read a balance sheet because losing imaginary money annoyed me more than I expected.',
                    'name' => 'Sofia Ortega',
                    'role' => 'Student trader · FinTech cohort',
                ],
            ] as $index => $testimonial)
                <figure
                    x-reveal
                    style="transition-delay: {{ $index * 120 }}ms"
                    class="flex h-full flex-col rounded-3xl border border-white/10 bg-white/[0.035] p-6 shadow-panel-soft backdrop-blur-xl transition duration-500 hover:-translate-y-1.5 hover:border-white/20"
                >
                    <div class="flex items-center gap-1 text-amber-300" aria-label="Rated 5 out of 5">
                        @for ($star = 0; $star < 5; $star++)
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2.75l2.9 5.88 6.49.94-4.7 4.58 1.11 6.46L12 17.56l-5.8 3.05 1.11-6.46-4.7-4.58 6.49-.94L12 2.75z" />
                            </svg>
                        @endfor
                    </div>

                    <blockquote class="mt-5 flex-1 text-sm leading-relaxed text-slate-300">
                        “{{ $testimonial['quote'] }}”
                    </blockquote>

                    <figcaption class="mt-6 flex items-center gap-3 border-t border-white/10 pt-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-brand-500/40 to-cyan-500/30 font-display text-sm font-bold text-white">
                            {{ Str::substr($testimonial['name'], 0, 1) }}
                        </span>
                        <span>
                            <span class="block text-sm font-semibold text-white">{{ $testimonial['name'] }}</span>
                            <span class="block text-xs text-slate-500">{{ $testimonial['role'] }}</span>
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
