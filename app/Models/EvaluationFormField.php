<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationFormField extends Model
{
    protected $fillable = [
        'evaluation_form_id',
        'label',
        'label_ar',
        'field_type',
        'options',
        'required',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
        'order' => 'integer',
    ];

    public function evaluationForm(): BelongsTo
    {
        return $this->belongsTo(EvaluationForm::class);
    }
    
    public function getLocalizedLabelAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->label_ar 
            ? $this->label_ar 
            : $this->label;
    }
}
