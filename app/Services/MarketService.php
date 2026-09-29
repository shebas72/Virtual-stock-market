<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class MarketService
{
    /**
     * Reset daily market data (call at market open).
     */
    public function resetDailyData(): void
    {
        Stock::active()->update([
            'opening_price' => DB::raw('current_price'),
            'previous_close' => DB::raw('current_price'),
            'day_high' => null,
            'day_low' => null,
            'volume' => 0,
        ]);
    }

    /**
     * Get market status.
     */
    public function isMarketOpen(): bool
    {
        $now = now();
        $dayOfWeek = $now->dayOfWeek;
        
        // Closed on weekends
        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            return false;
        }
        
        // Market hours: 9:30 AM - 4:00 PM (simplified)
        $hour = $now->hour;
        $minute = $now->minute;
        $time = $hour * 60 + $minute;
        
        return $time >= (9 * 60 + 30) && $time < (16 * 60);
    }
}