<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelRouteWaypoint extends Model
{
    protected $fillable = [
        'travel_route_id',
        'name',
        'order',
        'latitude',
        'longitude',
        'stop_type',
        'wait_time_minutes',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'order' => 'integer',
        'wait_time_minutes' => 'integer',
    ];

    public function travelRoute(): BelongsTo
    {
        return $this->belongsTo(TravelRoute::class);
    }
}
