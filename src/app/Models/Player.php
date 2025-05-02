<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Player extends Model
{
    protected $fillable = [
        'name',
        'archived',
        'user_id',
    ];

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    /**
     * Get the user associated with the player.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}