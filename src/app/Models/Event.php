<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'season_id',
        'name',
        'country_code',
        'date',
        'is_sprint',
        'double_points',
        'archived',
    ];

    protected $casts = [
        'date' => 'date',
        'is_sprint' => 'boolean',
        'double_points' => 'boolean',
    ];

    // Relationships to predictions
    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function qualifyingPositions()
    {
        return $this->hasMany(QualifyingPosition::class)->orderBy('position');
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}