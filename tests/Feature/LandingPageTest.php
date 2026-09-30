<?php

use App\Models\Portfolio;
use App\Models\Stock;
use App\Models\Tenant;
use App\Models\User;

it('renders the public landing page for guests', function () {
    $response = $this->get(route('landing'));

    $response->assertOk();
    $response->assertSee('Master the market');
    $response->assertSee('Create free account');
    $response->assertSee(route('register'), false);
    $response->assertSee(route('login'), false);
    $response->assertSee('Virtual capital');
    $response->assertSee('100,000');
});

it('exposes directives to Alpine by rooting the page at x-data', function () {
    $response = $this->get(route('landing'));

    $response->assertOk();

    // Alpine only initialises trees rooted at `x-data`, so the reveal,
    // counter and spotlight directives rely on this wrapper. Without it the
    // scroll-reveal styles keep the copy hidden.
    $response->assertSee('x-data="{}"', false);

    // The head guard drops the `js` class again if the bundle never boots.
    $response->assertSee("document.documentElement.classList.add('js')", false);
    $response->assertSee('window.__alpineReady', false);
});

it('shows the seeded market ticker and movers on the landing page', function () {
    Stock::create([
        'symbol' => 'ZZTOP',
        'name' => 'Zed Industries',
        'sector' => 'Technology',
        'current_price' => 150.00,
        'previous_close' => 120.00,
        'volume' => 900000,
    ]);

    $response = $this->get(route('landing'));

    $response->assertOk();
    $response->assertSee('ZZTOP');
    $response->assertSee('Zed Industries');
    $response->assertSee('+25.00%');
    $response->assertDontSee('Tracked by 1');
});

it('falls back to demo quotes when the market has not been seeded', function () {
    expect(Stock::count())->toBe(0);

    $response = $this->get(route('landing'));

    $response->assertOk();
    $response->assertSee('NVDA');
    $response->assertSee("Today's movers", false);
});

it('ranks the strongest portfolios on the landing page leaderboard', function () {
    $tenant = Tenant::create([
        'name' => 'Alpha Desk',
        'slug' => 'alpha-desk',
    ]);

    $leader = User::factory()->create([
        'name' => 'Leading Trader',
        'tenant_id' => $tenant->id,
    ]);
    Portfolio::create([
        'user_id' => $leader->id,
        'total_value' => 142870.00,
        'portfolio_return_percent' => 42.87,
        'total_trades' => 118,
    ]);

    $runnerUp = User::factory()->create([
        'name' => 'Second Trader',
        'tenant_id' => $tenant->id,
    ]);
    Portfolio::create([
        'user_id' => $runnerUp->id,
        'total_value' => 110000.00,
        'portfolio_return_percent' => 10.00,
        'total_trades' => 12,
    ]);

    $response = $this->get(route('landing'));

    $response->assertOk();
    $response->assertSee('Alpha Desk');
    $response->assertSee('+42.87%');
    $response->assertSeeInOrder(['Leading Trader', 'Second Trader']);
});

it('points authenticated traders at their dashboard instead of sign-up', function () {
    $user = User::factory()->create(['name' => 'Casey Trader']);

    $response = $this->actingAs($user)->get(route('landing'));

    $response->assertOk();
    $response->assertSee('Open my portfolio');
    $response->assertDontSee('Create free account');
});
