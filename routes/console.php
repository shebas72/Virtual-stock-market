<?php

use App\Services\FinnhubQuoteService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('stocks:refresh-quotes', function (FinnhubQuoteService $quoteService) {
    try {
        $result = $quoteService->refreshActiveStocks();
    } catch (RuntimeException $exception) {
        $this->error($exception->getMessage());

        return 1;
    }

    $this->info("Refreshed {$result['updated']} live quotes; {$result['failed']} failed.");

    return $result['failed'] > 0 ? 1 : 0;
})->purpose('Refresh active stock prices from Finnhub');

Schedule::command('stocks:refresh-quotes')->everyFiveMinutes()->withoutOverlapping();
