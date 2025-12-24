<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plate_number',
        'vehicle_type_id',
        'model',
        'color',
        'vin',
        'year',
        'status',
        'insurance_expiry',
        'registration_expiry',
        'notes',
    ];

    protected $casts = [
        'insurance_expiry' => 'date',
        'registration_expiry' => 'date',
        'year' => 'integer',
    ];

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function drivers(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, 'driver_vehicles')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
    
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    
    public function getCapacityAttribute(): int
    {
        return $this->vehicleType?->capacity ?? 0;
    }
    
    public function isAvailable(): bool
    {
        return $this->status === 'active';
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
