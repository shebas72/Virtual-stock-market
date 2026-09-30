<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'tenant_id', 'tenant_role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tenant_id' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isTenantOwner(): bool
    {
        return $this->tenant_role === 'owner';
    }

    /**
     * Get the portfolio for the user.
     */
    public function portfolio(): HasOne
    {
        return $this->hasOne(Portfolio::class);
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the watchlist items for the user.
     */
    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * Get the stocks the user is watching.
     */
    public function watchedStocks(): BelongsToMany
    {
        return $this->belongsToMany(Stock::class, 'watchlists')
            ->withTimestamps();
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get or create portfolio for user.
     */
    public function getOrCreatePortfolio(float $initialBalance = 100000.00): Portfolio
    {
        return $this->portfolio ?? Portfolio::create([
            'user_id' => $this->id,
            'cash_balance' => $initialBalance,
            'initial_balance' => $initialBalance,
            'total_value' => $initialBalance,
        ]);
    }
}
