<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'trip_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'credit' => 'success',
            'debit' => 'danger',
            'collection' => 'info',
            'remittance' => 'warning',
            'booking' => 'primary',
            'refund' => 'success',
            default => 'gray',
        };
    }
    
    public function getFormattedTypeAttribute(): string
    {
        return match ($this->type) {
            'credit' => __('moean.wallet.credit'),
            'debit' => __('moean.wallet.debit'),
            'collection' => __('moean.wallet.collection'),
            'remittance' => __('moean.wallet.remittance'),
            'booking' => __('moean.wallet.booking'),
            'refund' => __('moean.wallet.refund'),
            default => $this->type,
        };
    }
}
