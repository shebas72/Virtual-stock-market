<?php

namespace App\Services;

use App\Models\MarketData;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class FinnhubQuoteService
{
    public function refreshActiveStocks(): array
    {
        $apiKey = config('services.finnhub.key');

        if (! is_string($apiKey) || $apiKey === '') {
            throw new RuntimeException('Configure FINNHUB_API_KEY before refreshing live stock quotes.');
        }

        $updated = 0;
        $failed = 0;

        foreach (Stock::active()->get() as $stock) {
            try {
                $response = Http::acceptJson()
                    ->timeout(10)
                    ->get('https://finnhub.io/api/v1/quote', [
                        'symbol' => $stock->symbol,
                        'token' => $apiKey,
                    ]);
            } catch (ConnectionException $exception) {
                Log::warning('Finnhub quote request failed.', [
                    'symbol' => $stock->symbol,
                    'message' => $exception->getMessage(),
                ]);
                $failed++;

                continue;
            }

            $quote = $response->json();

            if (! $response->successful() || ! is_array($quote) || ! is_numeric($quote['c'] ?? null) || (float) $quote['c'] <= 0) {
                Log::warning('Finnhub returned an invalid quote.', [
                    'symbol' => $stock->symbol,
                    'status' => $response->status(),
                ]);
                $failed++;

                continue;
            }

            $price = round((float) $quote['c'], 2);
            $previousClose = is_numeric($quote['pc'] ?? null) && (float) $quote['pc'] > 0
                ? round((float) $quote['pc'], 2)
                : (float) $stock->previous_close;
            $openingPrice = is_numeric($quote['o'] ?? null) && (float) $quote['o'] > 0
                ? round((float) $quote['o'], 2)
                : (float) ($stock->opening_price ?? $price);
            $dayHigh = is_numeric($quote['h'] ?? null) && (float) $quote['h'] > 0
                ? max(round((float) $quote['h'], 2), $price)
                : max((float) ($stock->day_high ?? $price), $price);
            $dayLow = is_numeric($quote['l'] ?? null) && (float) $quote['l'] > 0
                ? min(round((float) $quote['l'], 2), $price)
                : min((float) ($stock->day_low ?? $price), $price);
            $timestamp = is_numeric($quote['t'] ?? null) && (int) $quote['t'] > 0
                ? Carbon::createFromTimestampUTC((int) $quote['t'])
                : now();

            $stock->update([
                'current_price' => $price,
                'previous_close' => $previousClose,
                'opening_price' => $openingPrice,
                'day_high' => $dayHigh,
                'day_low' => $dayLow,
            ]);

            MarketData::updateOrCreate(
                [
                    'stock_id' => $stock->id,
                    'timestamp' => $timestamp,
                    'interval' => '1min',
                ],
                [
                    'open' => $openingPrice,
                    'high' => $dayHigh,
                    'low' => $dayLow,
                    'close' => $price,
                    'volume' => 0,
                ],
            );

            $updated++;
        }

        return compact('updated', 'failed');
    }
}
