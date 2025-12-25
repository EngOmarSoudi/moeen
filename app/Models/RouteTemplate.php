<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RouteTemplate extends Model
{
    protected $fillable = [
        'origin_city',
        'origin_city_ar',
        'destination_city',
        'destination_city_ar',
        'base_price',
        'vehicle_type_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForVehicleType($query, $vehicleTypeId)
    {
        return $query->where(function ($q) use ($vehicleTypeId) {
            $q->where('vehicle_type_id', $vehicleTypeId)
              ->orWhereNull('vehicle_type_id');
        });
    }

    // Helpers
    public function getDisplayNameAttribute(): string
    {
        return "{$this->origin_city} → {$this->destination_city}";
    }

    public function getLocalizedOriginAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->origin_city_ar 
            ? $this->origin_city_ar 
            : $this->origin_city;
    }

    public function getLocalizedDestinationAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->destination_city_ar 
            ? $this->destination_city_ar 
            : $this->destination_city;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->base_price, 2) . ' SAR';
    }

    /**
     * Find the best matching template for a city pair
     */
    public static function findForCities(string $origin, string $destination, ?int $vehicleTypeId = null): ?self
    {
        return static::active()
            ->where('origin_city', $origin)
            ->where('destination_city', $destination)
            ->forVehicleType($vehicleTypeId)
            ->orderByRaw('vehicle_type_id IS NULL') // Prefer specific vehicle type
            ->first();
    }
}
