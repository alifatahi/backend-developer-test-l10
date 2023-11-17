<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    // Define the relationship to the AchievementTarget model.
    public function achievementTargets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AchievementTarget::class);
    }
}
