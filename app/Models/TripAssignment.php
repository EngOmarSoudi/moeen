<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'trip_id',
        'driver_id',
        'status',
        'sequence_number',
        'notes',
        'assigned_at',
        'confirmed_at',
        'started_at',
        'completed_at',
        'declined_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

    // Relationships
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed', 'in_progress']);
    }

    // Accessors
    public function isAccepted(): bool
    {
        return in_array($this->status, ['confirmed', 'in_progress', 'completed']);
    }

    public function canBeStarted(): bool
    {
        return $this->status === 'confirmed';
    }

    public function canBeCompleted(): bool
    {
        return $this->status === 'in_progress';
    }

    public function canBeDeclined(): bool
    {
        return $this->status === 'pending';
    }
}
