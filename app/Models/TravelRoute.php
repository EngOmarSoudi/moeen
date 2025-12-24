<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelRoute extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
        'origin',
        'destination',
        'distance_km',
        'duration_minutes',
        'route_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function waypoints(): HasMany
    {
        return $this->hasMany(TravelRouteWaypoint::class)->orderBy('order');
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
    
    public function getLocalizedNameAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar 
            ? $this->name_ar 
            : $this->name;
    }
    
    public function getFormattedDistanceAttribute(): string
    {
        return $this->distance_km ? number_format($this->distance_km, 1) . ' km' : '-';
    }
    
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_minutes) return '-';
        
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
    }
}
