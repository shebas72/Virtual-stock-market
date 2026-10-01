<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'subscription_plan',
        'subscription_price',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
        'subscription_plan_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant) {
            $defaultPlan = SubscriptionPlan::query()->where('is_default', true)->where('is_active', true)->first();
            if ($defaultPlan) {
                $tenant->subscription_plan_id ??= $defaultPlan->id;
                $tenant->subscription_plan ??= $defaultPlan->name;
                $tenant->subscription_price ??= $defaultPlan->effectivePrice();
            }

            $tenant->subscription_plan ??= 'starter';
            $tenant->subscription_price ??= 0;
            $tenant->subscription_status ??= 'trialing';
            $tenant->trial_ends_at ??= now()->addDays(SubscriptionSetting::defaultTrialDays());
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

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function hasAvailableUserSlot(bool $includePendingInvitations = true): bool
    {
        $userLimit = $this->subscriptionPlan?->user_limit;
        if ($userLimit === null) {
            return true;
        }

        $users = $this->users()->count();
        if ($includePendingInvitations) {
            $users += $this->invitations()
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->count();
        }

        return $users < $userLimit;
    }
}
