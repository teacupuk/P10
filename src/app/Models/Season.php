<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public $incrementing = false; // for year-based ID
    protected $keyType = 'int';
    protected $fillable = ['id', 'active'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}