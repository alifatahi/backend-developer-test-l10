<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    const COMMENT_WRITE_CODE = 'comment-write';
    const VIDEO_WATCH_CODE = 'video-watch';

    protected $fillable = [
        'name',
        'code',
    ];

    protected $user;
    public function relatedTo($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Define the relationship to the AchievementTarget model.
     */
    public function achievementTargets()
    {
        return $this->hasMany(AchievementTarget::class);
    }

    /**
     * All the user that has earned this achievement.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['is_last', 'created_at'])
            ->orderByPivot('created_at', 'desc');
    }

    /**
     * Define the relationship to the AchievementTarget model.
     */
    public static function hasTarget(int $target, string $code)
    {
        return Achievement::select(
                'achievements.id',
                'achievements.name',
                'achievements.code',
                'achievement_targets.target',
            )
            ->join('achievement_targets', 'achievements.id', 'achievement_targets.achievement_id')
            ->where('code', $code)
            ->where('target', '<=', $target)
            ->orderBy('target')
            ->first();
    }

    // /**
    //  * The next achievement available for the specific code after this level.
    //  */
    // public static function nextAvailableTo(int $userId)
    // {
    //     $currentAchivements = DB::table('achievement_user')
    //         ->where('user_id', $userId)
    //         ->pluck('achievement_id')
    //         ->toArray();

    //     return Achievement::whereNotIn('id', $currentAchivements)
    //         ->with(['achievementTargets' => function ($q) {
    //             $q->orderByPivit('target');
    //         }])->dd()
    //         ;//->groupBy('code');
    // }

}
