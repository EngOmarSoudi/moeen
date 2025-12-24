<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Trip extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'customer_id',
        'driver_id',
        'vehicle_id',
        'trip_type_id',
        'travel_route_id',
        'agent_id',
        'origin',
        'destination',
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
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'completed_at' => 'datetime',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'passenger_count' => 'integer',
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

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tripType(): BelongsTo
    {
        return $this->belongsTo(TripType::class);
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
}
