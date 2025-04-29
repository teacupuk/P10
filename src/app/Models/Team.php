<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    // Allow mass assignment on name and color
    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * Get the drivers that belong to this team.
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }
}