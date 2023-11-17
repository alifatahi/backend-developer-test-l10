<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Define the relationship to the BadgeTarget model.
    public function badgeTargets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BadgeTarget::class);
    }
}
