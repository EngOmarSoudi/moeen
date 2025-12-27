<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Trip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'customer_id',
        'vehicle_type_id',
        'travel_route_id',
        'route_template_id',
        'agent_id',
        'origin',
        'origin_lat',
        'origin_lng',
        'destination',
        'destination_lat',
        'destination_lng',
        'start_at',
        'completed_at',
        'status',
        'service_kind',
        'customer_segment',
        'trip_leg',
        'passenger_count',
        'amount',
        'discount',
        'final_amount',
        'hotel_name',
        'notes',
        'cancellation_reason',
        'created_by',
        // Customer details snapshots
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_nationality',
        'customer_document_type',
        'customer_document_no',
        'customer_issuing_authority',
        'customer_status',
        'customer_agent_name',
        'customer_notes',
        'customer_special_case_note',
        'customer_emergency_contact_name',
        'customer_emergency_contact_phone',
        'customer_emergency_contact_email',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'completed_at' => 'datetime',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'passenger_count' => 'integer',
        'origin_lat' => 'decimal:8',
        'origin_lng' => 'decimal:8',
        'destination_lat' => 'decimal:8',
        'destination_lng' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($trip) {
            if (!$trip->code) {
                $trip->code = 'TRP-' . strtoupper(Str::random(8));
            }
            if (!$trip->final_amount) {
                $trip->final_amount = $trip->amount - $trip->discount;
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }



    public function travelRoute(): BelongsTo
    {
        return $this->belongsTo(TravelRoute::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tripCustomers(): HasMany
    {
        return $this->hasMany(TripCustomer::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'trip_customers');
    }

    public function paymentCollections(): HasMany
    {
        return $this->hasMany(PaymentCollection::class);
    }
    
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }
    
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }
    
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }
    
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function isPaymentPending(): bool
    {
        $collected = $this->paymentCollections()->where('status', 'confirmed')->sum('amount');
        return $collected < $this->final_amount;
    }

    // Route Tracking
    public function trackingPoints(): HasMany
    {
        return $this->hasMany(TripTrackingPoint::class)->orderBy('recorded_at');
    }

    public function latestTrackingPoint(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TripTrackingPoint::class)->latestOfMany('recorded_at');
    }

    public function routeTemplate(): BelongsTo
    {
        return $this->belongsTo(RouteTemplate::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TripAssignment::class)->orderBy('sequence_number');
    }

    public function assignedDrivers(): BelongsToMany
    {
        return $this->belongsToMany(Driver::class, 'trip_assignments')
            ->withPivot('status', 'sequence_number', 'notes', 'assigned_at', 'confirmed_at', 'started_at', 'completed_at', 'declined_at')
            ->withTimestamps();
    }

    /**
     * Check if the driver followed the planned route within tolerance
     * @param float $toleranceMeters Maximum allowed deviation in meters
     */
    public function wasRouteFollowed(float $toleranceMeters = 500): bool
    {
        if (!$this->origin_lat || !$this->destination_lat) {
            return true; // No planned route to compare
        }

        $deviationPercentage = $this->getRouteDeviationPercentage($toleranceMeters);
        return $deviationPercentage <= 10; // Allow up to 10% deviation
    }

    /**
     * Calculate percentage of tracking points that deviated from planned route
     */
    public function getRouteDeviationPercentage(float $toleranceMeters = 500): float
    {
        $points = $this->trackingPoints;
        if ($points->isEmpty()) {
            return 0;
        }

        $deviatedCount = 0;
        foreach ($points as $point) {
            if (!$this->isPointOnRoute($point, $toleranceMeters)) {
                $deviatedCount++;
            }
        }

        return ($deviatedCount / $points->count()) * 100;
    }

    /**
     * Check if a tracking point is within tolerance of the planned route line
     */
    private function isPointOnRoute(TripTrackingPoint $point, float $toleranceMeters): bool
    {
        // Simple check: distance from the straight line between origin and destination
        $distance = $this->pointToLineDistance(
            $point->latitude,
            $point->longitude,
            $this->origin_lat,
            $this->origin_lng,
            $this->destination_lat,
            $this->destination_lng
        );

        return $distance <= $toleranceMeters;
    }

    /**
     * Calculate perpendicular distance from a point to a line segment
     */
    private function pointToLineDistance(
        float $pointLat,
        float $pointLng,
        float $lineStartLat,
        float $lineStartLng,
        float $lineEndLat,
        float $lineEndLng
    ): float {
        $earthRadius = 6371000; // meters

        // Convert to radians
        $lat1 = deg2rad($lineStartLat);
        $lng1 = deg2rad($lineStartLng);
        $lat2 = deg2rad($lineEndLat);
        $lng2 = deg2rad($lineEndLng);
        $latP = deg2rad($pointLat);
        $lngP = deg2rad($pointLng);

        // Cross-track distance formula
        $d13 = $this->haversineDistance($lineStartLat, $lineStartLng, $pointLat, $pointLng);
        $bearing13 = $this->calculateBearing($lineStartLat, $lineStartLng, $pointLat, $pointLng);
        $bearing12 = $this->calculateBearing($lineStartLat, $lineStartLng, $lineEndLat, $lineEndLng);

        return abs(asin(sin($d13 / $earthRadius) * sin(deg2rad($bearing13 - $bearing12))) * $earthRadius);
    }

    private function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000;
        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);
        $a = sin($latDelta / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDelta / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    private function calculateBearing(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $dLng = deg2rad($lng2 - $lng1);
        $y = sin($dLng) * cos($lat2);
        $x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dLng);
        return rad2deg(atan2($y, $x));
    }
}
