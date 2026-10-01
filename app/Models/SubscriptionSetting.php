<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSetting extends Model
{
    protected $fillable = [
        'trial_days',
        'payments_enabled',
        'stripe_enabled',
        'paypal_enabled',
    ];

    protected function casts(): array
    {
        return [
            'trial_days' => 'integer',
            'payments_enabled' => 'boolean',
            'stripe_enabled' => 'boolean',
            'paypal_enabled' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], ['trial_days' => 7]);
    }

    public static function defaultTrialDays(): int
    {
        return (int) (static::query()->value('trial_days') ?? 7);
    }
}
