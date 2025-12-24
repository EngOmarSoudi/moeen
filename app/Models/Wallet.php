<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Wallet extends Model
{
    protected $fillable = [
        'walletable_type',
        'walletable_id',
        'balance',
        'total_debt',
        'total_collected',
        'budget_limit',
        'budget_used',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_debt' => 'decimal:2',
        'total_collected' => 'decimal:2',
        'budget_limit' => 'decimal:2',
        'budget_used' => 'decimal:2',
    ];

    public function walletable(): MorphTo
    {
        return $this->morphTo();
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class)->orderBy('created_at', 'desc');
    }
    
    // Customer wallet methods
    public function addDebt(float $amount, ?Trip $trip = null, ?string $description = null, ?int $userId = null): WalletTransaction
    {
        $balanceBefore = $this->total_debt;
        $this->total_debt += $amount;
        $this->save();
        
        return $this->transactions()->create([
            'trip_id' => $trip?->id,
            'type' => 'debit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->total_debt,
            'description' => $description ?? 'Trip charge added',
            'created_by' => $userId,
        ]);
    }
    
    // Driver wallet methods
    public function addCollection(float $amount, ?Trip $trip = null, ?string $description = null, ?int $userId = null): WalletTransaction
    {
        $balanceBefore = $this->total_collected;
        $this->total_collected += $amount;
        $this->save();
        
        return $this->transactions()->create([
            'trip_id' => $trip?->id,
            'type' => 'collection',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->total_collected,
            'description' => $description ?? 'Payment collected from customer',
            'created_by' => $userId,
        ]);
    }
    
    public function remit(float $amount, ?string $description = null, ?int $userId = null): WalletTransaction
    {
        $balanceBefore = $this->total_collected;
        $this->total_collected -= $amount;
        $this->save();
        
        return $this->transactions()->create([
            'type' => 'remittance',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->total_collected,
            'description' => $description ?? 'Cash remitted to company',
            'created_by' => $userId,
        ]);
    }
    
    // Agent wallet methods
    public function useBookingBudget(float $amount, ?Trip $trip = null, ?string $description = null, ?int $userId = null): WalletTransaction
    {
        $balanceBefore = $this->budget_used;
        $this->budget_used += $amount;
        $this->save();
        
        return $this->transactions()->create([
            'trip_id' => $trip?->id,
            'type' => 'booking',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->budget_used,
            'description' => $description ?? 'Booking budget used',
            'created_by' => $userId,
        ]);
    }
    
    public function getAvailableBudgetAttribute(): float
    {   
        return ($this->budget_limit ?? 0) - $this->budget_used;
    }
    
    public function canAfford(float $amount): bool
    {
        return $this->available_budget >= $amount;
    }
}
