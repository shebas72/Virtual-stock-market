<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Tenant extends Model
{
    use HasFactory;

    public const SUBSCRIPTION_PLANS = [
        'starter' => 'Starter',
        'professional' => 'Professional',
        'enterprise' => 'Enterprise',
    ];

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'subscription_plan',
        'subscription_price',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant) {
            $tenant->subscription_plan ??= 'starter';
            $tenant->subscription_price ??= 0;
            $tenant->subscription_status ??= 'trialing';
            $tenant->trial_ends_at ??= now()->addDays(7);
        });
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'subscription_price' => 'decimal:2',
            'trial_ends_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
        ];
    }

    public function hasValidSubscription(): bool
    {
        return match ($this->subscription_status) {
            'active' => $this->subscription_ends_at === null || $this->subscription_ends_at->isFuture(),
            'trialing' => $this->trial_ends_at?->isFuture() ?? false,
            default => false,
        };
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(TenantInvitation::class);
    }
}
