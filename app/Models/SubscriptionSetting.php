<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSetting extends Model
{
    protected $fillable = ['trial_days'];

    protected function casts(): array
    {
        return ['trial_days' => 'integer'];
    }

    public static function defaultTrialDays(): int
    {
        return (int) (static::query()->value('trial_days') ?? 7);
    }
}