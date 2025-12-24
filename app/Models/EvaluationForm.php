<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationForm extends Model
{
    protected $fillable = [
        'title',
        'title_ar',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(EvaluationFormField::class)->orderBy('order');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(TripEvaluation::class);
    }
    
    public function getLocalizedTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->title_ar 
            ? $this->title_ar 
            : $this->title;
    }
}
