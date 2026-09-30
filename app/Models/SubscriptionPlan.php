<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_limit',
        'duration_count',
        'duration_unit',
        'price',
        'discounted_price',
        'is_active',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'user_limit' => 'integer',
            'duration_count' => 'integer',
            'price' => 'decimal:2',
            'discounted_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function effectivePrice(): string
    {
        return $this->discounted_price ?? $this->price;
    }

    public function termLabel(): string
    {
        $unit = $this->duration_unit === 'day' ? 'day' : 'month';

        return $this->duration_count.' '.($this->duration_count === 1 ? $unit : $unit.'s');
    }
}