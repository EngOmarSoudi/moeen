<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentCollection extends Model
{
    protected $fillable = [
        'trip_id',
        'driver_id',
        'amount',
        'received',
        'change',
        'method',
        'status',
        'notes',
        'confirmed_by',
        'confirmed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received' => 'decimal:2',
        'change' => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
    
    public function confirm(int $userId): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_by' => $userId,
            'confirmed_at' => now(),
        ]);
        
        // Update driver wallet when payment is confirmed
        if ($this->driver) {
            $driverWallet = $this->driver->getOrCreateWallet();
            $driverWallet->addCollection($this->amount, $this->trip, 'Payment collected for trip ' . $this->trip->code, $userId);
        }
    }
    
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'partial' => 'info',
            'canceled' => 'danger',
            default => 'gray',
        };
    }
}
