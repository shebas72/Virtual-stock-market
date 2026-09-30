<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioHolding extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'portfolio_holdings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'portfolio_id',
        'stock_id',
        'quantity',
        'average_cost',
        'current_value',
        'unrealized_pnl',
        'unrealized_pnl_percent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'average_cost' => 'decimal:2',
            'current_value' => 'decimal:2',
            'unrealized_pnl' => 'decimal:2',
            'unrealized_pnl_percent' => 'decimal:4',
        ];
    }

    /**
     * Get the portfolio that owns the holding.
     */
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Get the stock for the holding.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Update the holding's current value and P&L.
     */
    public function updateCurrentValue(): void
    {
        $currentPrice = $this->stock->current_price;
        $this->current_value = round($this->quantity * $currentPrice, 2);
        $this->unrealized_pnl = round($this->current_value - ($this->quantity * $this->average_cost), 2);
        $this->unrealized_pnl_percent = $this->average_cost > 0
            ? round(($this->unrealized_pnl / ($this->quantity * $this->average_cost)) * 100, 4)
            : 0;
        $this->save();
    }

    /**
     * Calculate total cost basis.
     */
    public function getTotalCostAttribute(): float
    {
        return round($this->quantity * $this->average_cost, 2);
    }
}
