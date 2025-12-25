<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'nationality',
        'document_type',
        'document_no',
        'issuing_authority',
        'status_id',
        'agent_id',
        'notes',
        'special_case_note',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_email',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(CustomerStatus::class, 'status_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function relatives(): HasMany
    {
        return $this->hasMany(CustomerRelative::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
    
    public function tripCustomers(): HasMany
    {
        return $this->hasMany(TripCustomer::class);
    }
    
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }
    
    public function getOrCreateWallet(): Wallet
    {
        return $this->wallet ?? $this->wallet()->create();
    }
    
    public function getTotalDebtAttribute(): float
    {
        $wallet = $this->wallet;
        return $wallet ? $wallet->total_debt : 0;
    }
}
