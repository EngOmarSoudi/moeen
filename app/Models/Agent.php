<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'commission_type',
        'commission_value',
        'credit_limit',
        'credit_used',
        'status',
        'notes',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'credit_used' => 'decimal:2',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
    
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }
    
    public function getOrCreateWallet(): Wallet
    {
        return $this->wallet ?? $this->wallet()->create([
            'budget_limit' => $this->credit_limit,
        ]);
    }
    
    public function getAvailableBudgetAttribute(): float
    {
        $wallet = $this->wallet;
        return $wallet ? ($wallet->budget_limit - $wallet->budget_used) : 0;
    }
    
    public function canBook(float $amount): bool
    {
        return $this->available_budget >= $amount;
    }
}
