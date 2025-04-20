<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'archived',
    ];

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }
}