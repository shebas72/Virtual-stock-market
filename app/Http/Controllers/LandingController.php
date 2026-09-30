<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Stock;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSetting;
use App\Models\User;
use Throwable;

class LandingController extends Controller
{
    /**
     * Quotes rendered inside the hero terminal card.
     */
    private const HERO_QUOTE_COUNT = 4;

    /**
     * Quotes rendered inside the scrolling market ticker.
     */
    private const TICKER_QUOTE_COUNT = 14;

    /**
     * Virtual cash every new trader starts with. Mirrors the default of
     * User::getOrCreatePortfolio().
     */
    private const STARTING_BALANCE = 100000;

    /**
     * How often the market tape is refreshed by the scheduler
     * (see Schedule::command('stocks:refresh-quotes') in routes/console.php).
     */
    private const QUOTE_REFRESH_MINUTES = 5;

    /**
     * Trial length advertised when no subscription_settings row exists yet.
     * Mirrors the column default of the migration that creates the table.
     */
    private const FALLBACK_TRIAL_DAYS = 7;

    /**
     * Marketing copy for the plans an administrator ships with. Every plan
     * runs the identical platform and only differs by seat allowance, so the
     * bullets stay honest — nothing is gated that the application does not
     * actually gate. Unknown plans fall back to the constants below.
     *
     * @var array<string, array{tagline: string, extra: string}>
     */
    private const PLAN_COPY = [
        'Starter' => [
            'tagline' => 'Solo traders, study groups and first-time leagues.',
            'extra' => 'Private league with its own scoped leaderboard',
        ],
        'Professional' => [
            'tagline' => 'Classrooms, clubs and trading desks with a full cohort.',
            'extra' => 'Room for a whole cohort with roles and suspension',
        ],
        'Enterprise' => [
            'tagline' => 'Large programmes running many cohorts side by side.',
            'extra' => 'Scales to whole departments and multi-term cohorts',
        ],
    ];

    private const PLAN_FALLBACK_TAGLINE = 'A private market for your whole trading group.';

    private const PLAN_FALLBACK_EXTRA = 'Everything the platform does, with room to grow.';

    /**
     * Plan tiers shown when the subscription tables are not reachable, so the
     * pricing section still renders on an unmigrated install. Mirrors the
     * tiers seeded by the migration that creates subscription_plans.
     *
     * @var array<int, array<string, mixed>>
     */
    private const DEMO_PLANS = [
        ['name' => 'Starter', 'seats' => 5, 'list_price' => 0.0, 'price' => 0.0, 'term' => '1 month'],
        ['name' => 'Professional', 'seats' => 25, 'list_price' => 0.0, 'price' => 0.0, 'term' => '1 month'],
        ['name' => 'Enterprise', 'seats' => 100, 'list_price' => 0.0, 'price' => 0.0, 'term' => '1 month'],
    ];

    /**
     * Representative market snapshot used when the database is not
     * seeded yet, so the marketing page never breaks on a fresh install.
     *
     * @var array<int, array<string, mixed>>
     */
    private const DEMO_QUOTES = [
        ['symbol' => 'NVDA', 'name' => 'NVIDIA Corporation', 'sector' => 'Technology', 'price' => 1214.40, 'previous_close' => 1188.05, 'change' => 26.35, 'change_percent' => 2.22, 'volume' => 48200000],
        ['symbol' => 'AAPL', 'name' => 'Apple Inc.', 'sector' => 'Technology', 'price' => 232.18, 'previous_close' => 230.94, 'change' => 1.24, 'change_percent' => 0.54, 'volume' => 54100000],
        ['symbol' => 'MSFT', 'name' => 'Microsoft Corporation', 'sector' => 'Technology', 'price' => 468.92, 'previous_close' => 471.10, 'change' => -2.18, 'change_percent' => -0.46, 'volume' => 21300000],
        ['symbol' => 'TSLA', 'name' => 'Tesla, Inc.', 'sector' => 'Consumer', 'price' => 337.55, 'previous_close' => 328.40, 'change' => 9.15, 'change_percent' => 2.79, 'volume' => 89700000],
        ['symbol' => 'AMZN', 'name' => 'Amazon.com, Inc.', 'sector' => 'Consumer', 'price' => 214.36, 'previous_close' => 216.02, 'change' => -1.66, 'change_percent' => -0.77, 'volume' => 39400000],
        ['symbol' => 'GOOGL', 'name' => 'Alphabet Inc.', 'sector' => 'Communication', 'price' => 190.74, 'previous_close' => 188.20, 'change' => 2.54, 'change_percent' => 1.35, 'volume' => 27800000],
        ['symbol' => 'NFLX', 'name' => 'Netflix, Inc.', 'sector' => 'Communication', 'price' => 742.61, 'previous_close' => 736.08, 'change' => 6.53, 'change_percent' => 0.89, 'volume' => 6100000],
        ['symbol' => 'JPM', 'name' => 'JPMorgan Chase & Co.', 'sector' => 'Financial', 'price' => 246.90, 'previous_close' => 249.31, 'change' => -2.41, 'change_percent' => -0.97, 'volume' => 9400000],
        ['symbol' => 'V', 'name' => 'Visa Inc.', 'sector' => 'Financial', 'price' => 318.42, 'previous_close' => 315.88, 'change' => 2.54, 'change_percent' => 0.80, 'volume' => 7300000],
        ['symbol' => 'WMT', 'name' => 'Walmart Inc.', 'sector' => 'Consumer', 'price' => 88.16, 'previous_close' => 87.02, 'change' => 1.14, 'change_percent' => 1.31, 'volume' => 15800000],
        ['symbol' => 'JNJ', 'name' => 'Johnson & Johnson', 'sector' => 'Healthcare', 'price' => 162.05, 'previous_close' => 163.44, 'change' => -1.39, 'change_percent' => -0.85, 'volume' => 8100000],
        ['symbol' => 'DIS', 'name' => 'The Walt Disney Company', 'sector' => 'Consumer', 'price' => 115.72, 'previous_close' => 113.96, 'change' => 1.76, 'change_percent' => 1.54, 'volume' => 12600000],
    ];

    /**
     * Demo trader standings shown when nobody has traded yet.
     *
     * @var array<int, array<string, mixed>>
     */
    private const DEMO_TRADERS = [
        ['rank' => 1, 'name' => 'Aisha Rahman', 'workspace' => 'Alpha Desk', 'return_percent' => 42.87, 'total_value' => 142870.0, 'trades' => 118],
        ['rank' => 2, 'name' => 'Marco Vidal', 'workspace' => 'The Bulls', 'return_percent' => 31.24, 'total_value' => 131240.0, 'trades' => 96],
        ['rank' => 3, 'name' => 'Priya Nair', 'workspace' => 'FinTech Cohort', 'return_percent' => 27.65, 'total_value' => 127650.0, 'trades' => 143],
    ];

    /**
     * Representative market metrics used when the stocks table is empty.
     *
     * @var array<string, int>
     */
    private const DEMO_STATS = [
        'stocks' => 12,
        'sectors' => 6,
        'advancing' => 8,
        'declining' => 4,
    ];

    /**
     * Display the public landing page.
     */
    public function index()
    {
        $quotes = $this->quotes();
        $trialDays = $this->trialDays();

        return view('landing.index', [
            'quotes' => $quotes,
            'heroQuotes' => array_slice($quotes, 0, self::HERO_QUOTE_COUNT),
            'traders' => $this->topTraders(),
            'stats' => $this->marketStats(),
            'startingBalance' => self::STARTING_BALANCE,
            'refreshMinutes' => self::QUOTE_REFRESH_MINUTES,
            'plans' => $this->plans(),
            'trialDays' => $trialDays,
            // Concrete, reassuring date: what a workspace created today gets.
            'trialEndsOn' => now()->addDays($trialDays)->format('F j, Y'),
        ]);
    }

    /**
     * Subscription plans to advertise, sorted from the smallest seat allowance
     * to the largest so the pricing ladder reads left to right. Seats, terms
     * and prices come from whatever an administrator has configured; only the
     * wording lives here.
     *
     * @return array<int, array<string, mixed>>
     */
    private function plans(): array
    {
        $rows = $this->planRows();
        // The middle tier is the one to draw the eye to; a plan standing alone
        // (or a two-plan ladder) is never dressed up as "popular".
        $popularIndex = count($rows) >= 3 ? intdiv(count($rows), 2) : -1;

        return collect($rows)
            ->map(function (array $row, int $index) use ($popularIndex) {
                $copy = self::PLAN_COPY[$row['name']] ?? null;

                return [
                    ...$row,
                    'tagline' => $copy['tagline'] ?? self::PLAN_FALLBACK_TAGLINE,
                    'features' => $this->planFeatures($row['seats'], $copy['extra'] ?? self::PLAN_FALLBACK_EXTRA),
                    'popular' => $index === $popularIndex,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Raw plan tiers straight from the database, falling back to the seeded
     * tiers when the table is missing or the database is unreachable.
     *
     * @return array<int, array<string, mixed>>
     */
    private function planRows(): array
    {
        try {
            $plans = SubscriptionPlan::query()
                ->where('is_active', true)
                ->orderBy('user_limit')
                ->orderBy('id')
                ->get();
        } catch (Throwable) {
            return self::DEMO_PLANS;
        }

        if ($plans->isEmpty()) {
            return self::DEMO_PLANS;
        }

        return $plans
            ->map(fn (SubscriptionPlan $plan) => [
                'name' => $plan->name,
                'seats' => $plan->user_limit,
                'list_price' => (float) $plan->price,
                'price' => (float) $plan->effectivePrice(),
                'term' => $plan->termLabel(),
                'is_default' => (bool) $plan->is_default,
            ])
            ->all();
    }

    /**
     * Per-plan bullets. The application only varies the seat allowance between
     * plans, so the shared capabilities are repeated on every tier rather than
     * invented as extras.
     *
     * @return array<int, string>
     */
    private function planFeatures(int $seats, string $extra): array
    {
        return [
            'Up to '.$seats.' traders, each with a private portfolio and $'.number_format(self::STARTING_BALANCE).' of simulated cash',
            'Live Finnhub quotes refreshed every '.self::QUOTE_REFRESH_MINUTES.' minutes',
            'Portfolio analytics, full trade history and scoped leaderboards',
            'Owner controls: email invites, expiring links, roles and suspension',
            $extra,
        ];
    }

    /**
     * Length of the free trial every new workspace starts with, as configured
     * by an administrator on the subscription plans screen.
     */
    private function trialDays(): int
    {
        try {
            $trialDays = SubscriptionSetting::defaultTrialDays();
        } catch (Throwable) {
            return self::FALLBACK_TRIAL_DAYS;
        }

        return $trialDays > 0 ? $trialDays : self::FALLBACK_TRIAL_DAYS;
    }

    /**
     * Build the quote list used by the hero card and the market ticker.
     *
     * @return array<int, array<string, mixed>>
     */
    private function quotes(): array
    {
        try {
            $stocks = Stock::active()
                ->orderByDesc('volume')
                ->limit(self::TICKER_QUOTE_COUNT)
                ->get();
        } catch (Throwable) {
            return self::DEMO_QUOTES;
        }

        if ($stocks->isEmpty()) {
            return self::DEMO_QUOTES;
        }

        return $stocks->map(function (Stock $stock) {
            $price = (float) $stock->current_price;
            $previousClose = (float) ($stock->previous_close ?: $stock->opening_price ?: $price);
            $change = $price - $previousClose;

            return [
                'symbol' => $stock->symbol,
                'name' => $stock->name,
                'sector' => $stock->sector ?: 'Diversified',
                'price' => round($price, 2),
                'previous_close' => round($previousClose, 2),
                'change' => round($change, 2),
                'change_percent' => $previousClose > 0 ? round(($change / $previousClose) * 100, 2) : 0.0,
                'volume' => (int) $stock->volume,
            ];
        })->all();
    }

    /**
     * Top three portfolios across the whole platform.
     *
     * @return array<int, array<string, mixed>>
     */
    private function topTraders(): array
    {
        try {
            $traders = User::query()
                ->whereHas('portfolio')
                ->with(['portfolio', 'tenant'])
                ->orderByDesc(
                    Portfolio::query()
                        ->select('portfolio_return_percent')
                        ->whereColumn('portfolios.user_id', 'users.id')
                )
                ->limit(3)
                ->get()
                ->filter(fn (User $user) => $user->portfolio !== null)
                ->values()
                ->map(fn (User $user, int $index) => [
                    'rank' => $index + 1,
                    'name' => $user->name,
                    'workspace' => $user->tenant->name ?? 'Independent desk',
                    'return_percent' => round((float) $user->portfolio->portfolio_return_percent, 2),
                    'total_value' => round((float) $user->portfolio->total_value, 2),
                    'trades' => (int) $user->portfolio->total_trades,
                ])
                ->all();
        } catch (Throwable) {
            return self::DEMO_TRADERS;
        }

        return $traders !== [] ? $traders : self::DEMO_TRADERS;
    }

    /**
     * Market metrics for the animated statistics strip.
     *
     * The numbers are read from the seeded market; only when no active stocks
     * exist yet (a fresh install) does the page fall back to the demo snapshot
     * so the strip never collapses to zeroes.
     *
     * @return array<string, int>
     */
    private function marketStats(): array
    {
        try {
            $stocks = Stock::active()->count();

            if ($stocks === 0) {
                return self::DEMO_STATS;
            }

            return [
                'stocks' => $stocks,
                'sectors' => Stock::active()->distinct()->count('sector'),
                'advancing' => Stock::active()->whereColumn('current_price', '>', 'previous_close')->count(),
                'declining' => Stock::active()->whereColumn('current_price', '<', 'previous_close')->count(),
            ];
        } catch (Throwable) {
            return self::DEMO_STATS;
        }
    }
}
