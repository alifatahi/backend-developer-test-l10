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

    /**
     * Define the relationship to the BadgeTarget model.
     */
    public function badgeTargets()
    {
        return $this->hasMany(BadgeTarget::class);
    }

    /**
     * Define the relationship to the AchievementTarget model.
     */
    public static function hasTarget(int $target)
    {
        return Badge::select(
                'badges.id',
                'badges.name',
                'badge_targets.target',
            )
            ->join('badge_targets', 'badges.id', 'badge_targets.badge_id')
            ->where('target', '<=', $target)
            ->orderBy('target', 'desc')
            ->first();
    }
}
