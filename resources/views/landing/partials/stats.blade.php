<!-- Platform statistics -->
<section class="relative py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-6">
            <x-landing.stat
                :value="$startingBalance"
                prefix="$"
                label="Virtual capital"
                hint="Every new trader starts here"
            />
            <x-landing.stat
                :value="$stats['stocks']"
                label="Stocks tracked"
                hint="Technology to healthcare"
            />
            <x-landing.stat
                :value="$stats['sectors']"
                label="Sectors covered"
                hint="Spread your risk properly"
            />
            <x-landing.stat
                :value="$refreshMinutes"
                suffix=" min"
                label="Quote refresh"
                hint="Live prices from Finnhub"
            />
        </div>
    </div>
</section>
