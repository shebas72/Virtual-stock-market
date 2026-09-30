<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'symbol',
        'name',
        'description',
        'sector',
        'industry',
        'current_price',
        'opening_price',
        'previous_close',
        'day_high',
        'day_low',
        'volume',
        'market_cap',
        'pe_ratio',
        'dividend_yield',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'current_price' => 'decimal:2',
            'opening_price' => 'decimal:2',
            'previous_close' => 'decimal:2',
            'day_high' => 'decimal:2',
            'day_low' => 'decimal:2',
            'volume' => 'integer',
            'market_cap' => 'decimal:2',
            'pe_ratio' => 'decimal:2',
            'dividend_yield' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the transactions for the stock.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the market data for the stock.
     */
    public function marketData(): HasMany
    {
        return $this->hasMany(MarketData::class);
    }

    /**
     * Get the users watching this stock.
     */
    public function watchlists(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'watchlists')
            ->withTimestamps();
    }

    /**
     * Get the portfolio holdings for this stock.
     */
    public function portfolioHoldings(): HasMany
    {
        return $this->hasMany(PortfolioHolding::class);
    }

    /**
     * Calculate the price change from previous close.
     */
    public function getPriceChangeAttribute(): ?float
    {
        if ($this->previous_close && $this->current_price) {
            return round($this->current_price - $this->previous_close, 2);
        }

        return null;
    }

    /**
     * Calculate the price change percentage from previous close.
     */
    public function getPriceChangePercentAttribute(): ?float
    {
        if ($this->previous_close && $this->current_price && $this->previous_close > 0) {
            return round((($this->current_price - $this->previous_close) / $this->previous_close) * 100, 4);
        }

        return null;
    }

    /**
     * Scope to get only active stocks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search stocks by symbol or name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('symbol', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope to filter by sector.
     */
    public function scopeBySector($query, $sector)
    {
        return $query->where('sector', $sector);
    }
}
