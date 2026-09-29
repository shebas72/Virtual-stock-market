<?php

use App\Models\MarketData;
use App\Models\Stock;
use App\Services\FinnhubQuoteService;
use Illuminate\Support\Facades\Http;

it('updates stock prices and records Finnhub quote history', function () {
    config(['services.finnhub.key' => 'test-token']);

    $stock = Stock::create([
        'symbol' => 'AAPL',
        'name' => 'Apple Inc.',
        'current_price' => 178.50,
        'previous_close' => 177.80,
        'volume' => 1000,
    ]);

    Http::fake([
        'finnhub.io/api/v1/quote*' => Http::response([
            'c' => 190.25,
            'h' => 192.00,
            'l' => 188.50,
            'o' => 189.00,
            'pc' => 188.00,
            't' => 1760000000,
        ]),
    ]);

    $result = app(FinnhubQuoteService::class)->refreshActiveStocks();

    expect($result)->toBe(['updated' => 1, 'failed' => 0]);
    expect((float) $stock->fresh()->current_price)->toBe(190.25);
    expect((float) $stock->fresh()->previous_close)->toBe(188.00);
    expect(MarketData::where('stock_id', $stock->id)->where('interval', '1min')->count())->toBe(1);

    Http::assertSent(fn ($request) => $request['symbol'] === 'AAPL' && $request['token'] === 'test-token');
});