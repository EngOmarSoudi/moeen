<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'license_number',
        'license_expiry',
        'id_number',
        'status',
        'user_id',
        'photo',
        'notes',
        'rating',
        'total_trips',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'rating' => 'decimal:2',
        'total_trips' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'driver_vehicles')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(DriverShift::class);
    }

    public function lastLocation(): HasOne
    {
        return $this->hasOne(DriverLastLocation::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function paymentCollections(): HasMany
    {
        return $this->hasMany(PaymentCollection::class);
    }
    
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }
    
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }
    
    public function getOrCreateWallet(): Wallet
    {
        return $this->wallet ?? $this->wallet()->create();
    }
    
    public function getTotalCollectedAttribute(): float
    {
        $wallet = $this->wallet;
        return $wallet ? $wallet->total_collected : 0;
    }
    
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
    
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TripAssignment::class)->orderBy('assigned_at', 'desc');
    }

    public function assignedTrips(): BelongsToMany
    {
        return $this->belongsToMany(Trip::class, 'trip_assignments')
            ->withPivot('status', 'sequence_number', 'notes', 'assigned_at', 'confirmed_at', 'started_at', 'completed_at', 'declined_at')
            ->withTimestamps();
    }

    public function activeAssignments()
    {
        return $this->assignments()->active();
    }
}