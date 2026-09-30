<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'stock_id',
        'type',
        'quantity',
        'price_per_share',
        'total_amount',
        'commission',
        'status',
        'notes',
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
            'price_per_share' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'commission' => 'decimal:2',
        ];
    }

    /**
     * Get the user that made the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the stock for the transaction.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Scope to get only completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get only buy transactions.
     */
    public function scopeBuys($query)
    {
        return $query->where('type', 'buy');
    }

    /**
     * Scope to get only sell transactions.
     */
    public function scopeSells($query)
    {
        return $query->where('type', 'sell');
    }

    /**
     * Scope to get transactions for a specific stock.
     */
    public function scopeForStock($query, $stockId)
    {
        return $query->where('stock_id', $stockId);
    }

    /**
     * Scope to get recent transactions.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Calculate total cost including commission.
     */
    public function getTotalCostAttribute(): float
    {
        return round($this->total_amount + $this->commission, 2);
    }

    /**
     * Get formatted transaction type.
     */
    public function getFormattedTypeAttribute(): string
    {
        return ucfirst($this->type);
    }

    /**
     * Check if transaction is a buy.
     */
    public function isBuy(): bool
    {
        return $this->type === 'buy';
    }

    /**
     * Check if transaction is a sell.
     */
    public function isSell(): bool
    {
        return $this->type === 'sell';
    }
}
