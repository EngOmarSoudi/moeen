<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_id',
        'department',
        'job_title',
        'hired_at',
        'birth_date',
        'salary',
        'emergency_contact',
        'address',
        'notes',
        'status',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'birth_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
