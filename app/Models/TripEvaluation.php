<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TripEvaluation extends Model
{
    protected $fillable = [
        'trip_id',
        'evaluation_form_id',
        'evaluator_id',
        'target_type',
        'target_id',
        'score',
        'answers',
        'comments',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'answers' => 'array',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function evaluationForm(): BelongsTo
    {
        return $this->belongsTo(EvaluationForm::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
