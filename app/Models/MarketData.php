<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketData extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stock_id',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'timestamp',
        'interval',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'open' => 'decimal:2',
            'high' => 'decimal:2',
            'low' => 'decimal:2',
            'close' => 'decimal:2',
            'volume' => 'integer',
            'timestamp' => 'datetime',
        ];
    }

    /**
     * Get the stock for the market data.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Scope to get data for a specific interval.
     */
    public function scopeInterval($query, $interval)
    {
        // Map common interval names to database values
        $intervalMap = [
            '1m' => '1min',
            '5m' => '5min',
            '15m' => '15min',
            '30m' => '30min',
            '1h' => '1h',
            '4h' => '4h',
            '1d' => '1d',
            '1w' => '1w',
            '1mo' => '1M',
            '1M' => '1M',
        ];

        $mappedInterval = $intervalMap[$interval] ?? $interval;

        return $query->where('interval', $mappedInterval);
    }

    /**
     * Scope to get data within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('timestamp', [$startDate, $endDate]);
    }

    /**
     * Scope to get recent data.
     */
    public function scopeRecent($query, $limit = 100)
    {
        return $query->orderBy('timestamp', 'desc')->limit($limit);
    }

    /**
     * Scope to get data for a specific stock.
     */
    public function scopeForStock($query, $stockId)
    {
        return $query->where('stock_id', $stockId);
    }

    /**
     * Get OHLC data formatted for charts.
     */
    public function getOhlcDataAttribute(): array
    {
        return [
            'timestamp' => $this->timestamp->toIso8601String(),
            'open' => $this->open,
            'high' => $this->high,
            'low' => $this->low,
            'close' => $this->close,
            'volume' => $this->volume,
        ];
    }
}
