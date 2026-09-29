<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'cash_balance',
        'initial_balance',
        'total_value',
        'portfolio_return',
        'portfolio_return_percent',
        'total_trades',
        'winning_trades',
        'losing_trades',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cash_balance' => 'decimal:2',
            'initial_balance' => 'decimal:2',
            'total_value' => 'decimal:2',
            'portfolio_return' => 'decimal:2',
            'portfolio_return_percent' => 'decimal:4',
            'total_trades' => 'integer',
            'winning_trades' => 'integer',
            'losing_trades' => 'integer',
        ];
    }

    /**
     * Get the user that owns the portfolio.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the holdings for the portfolio.
     */
    public function holdings(): HasMany
    {
        return $this->hasMany(PortfolioHolding::class);
    }

    /**
     * Get the transactions for the portfolio's user.
     */
    public function transactions(): HasMany
    {
        return $this->hasManyThrough(Transaction::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    /**
     * Calculate the total value of the portfolio including holdings.
     */
    public function calculateTotalValue(): float
    {
        $holdingsValue = $this->holdings->sum('current_value');
        return round($this->cash_balance + $holdingsValue, 2);
    }

    /**
     * Calculate the portfolio return.
     */
    public function calculateReturn(): array
    {
        $totalValue = $this->calculateTotalValue();
        $return = $totalValue - $this->initial_balance;
        $returnPercent = $this->initial_balance > 0 ? ($return / $this->initial_balance) * 100 : 0;
        
        return [
            'total_value' => $totalValue,
            'return' => round($return, 2),
            'return_percent' => round($returnPercent, 4),
        ];
    }

    /**
     * Update portfolio metrics.
     */
    public function updateMetrics(): void
    {
        $metrics = $this->calculateReturn();
        $this->total_value = $metrics['total_value'];
        $this->portfolio_return = $metrics['return'];
        $this->portfolio_return_percent = $metrics['return_percent'];
        $this->save();
    }

    /**
     * Check if user can afford a purchase.
     */
    public function canAfford(float $amount): bool
    {
        return $this->cash_balance >= $amount;
    }

    /**
     * Check if user has enough shares to sell.
     */
    public function hasEnoughShares(int $stockId, int $quantity): bool
    {
        $holding = $this->holdings()->where('stock_id', $stockId)->first();
        return $holding && $holding->quantity >= $quantity;
    }

    /**
     * Get diversified holdings summary.
     */
    public function getSectorAllocation(): array
    {
        $allocation = [];
        $totalValue = $this->holdings->sum('current_value');
        
        if ($totalValue == 0) {
            return [];
        }

        foreach ($this->holdings as $holding) {
            $sector = $holding->stock->sector ?? 'Other';
            if (!isset($allocation[$sector])) {
                $allocation[$sector] = 0;
            }
            $allocation[$sector] += $holding->current_value;
        }

        // Convert to percentages
        foreach ($allocation as $sector => $value) {
            $allocation[$sector] = round(($value / $totalValue) * 100, 2);
        }

        return $allocation;
    }

    /**
     * Get top performing holdings.
     */
    public function getTopPerformers(int $limit = 5): \Illuminate\Support\Collection
    {
        return $this->holdings()
            ->join('stocks', 'portfolio_holdings.stock_id', '=', 'stocks.id')
            ->orderBy('portfolio_holdings.unrealized_pnl_percent', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get worst performing holdings.
     */
    public function getWorstPerformers(int $limit = 5): \Illuminate\Support\Collection
    {
        return $this->holdings()
            ->join('stocks', 'portfolio_holdings.stock_id', '=', 'stocks.id')
            ->orderBy('portfolio_holdings.unrealized_pnl_percent', 'asc')
            ->limit($limit)
            ->get();
    }
}