<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Team;

class Driver extends Model
{
    // Allow mass assignment of driver fields, including team_id
    protected $fillable = [
        'id',
        'name',
        'nationality',
        'archived',
        'team_id',
    ];

    /**
     * Get the team that the driver belongs to.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
