<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watchedLessons()
    {
        return $this->lessons()
            ->wherePivot('watched', true);
    }

    /**
     * All the badges that user has earned.
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class)
            ->withPivot(['is_last', 'created_at'])
            ->orderByPivot('created_at', 'desc');
    }

    /**
     * The last badge that user has earned.
     */
    public function currentBadge(): Badge
    {
        return $this->belongsToMany(Badge::class)
            ->withPivot(['is_last', 'created_at'])
            ->wherePivot('is_last', true)
            ->join('badge_targets', 'badges.id', 'badge_targets.badge_id')
            ->select('badges.id', 'name', 'target')
            ->first();
    }

    /**
     * The next achievements in each category that a user can earn.
     */
    public function nextAvailableBadge(): \Illuminate\Support\Collection|null
    {
        $currentBadgeTarget = $this->currentBadge()->target;

        return Badge::query()
            ->join('badge_targets AS bt', 'badges.id', 'bt.badge_id')
            ->where('target', '>', $currentBadgeTarget)
            ->orderBy('target')
            ->first();
    }

    /**
     * All the achievements that user has earned.
     */
    public function achievements(?string $code = null)
    {
        return $this->belongsToMany(Achievement::class)
            ->select('achievements.id', 'name', 'code', 'user_id', 'level', 'title', 'is_last', 'achievement_user.created_at')
            ->when($code, function ($q, $code) {
                $q->where('code', $code);
            })
            ->orderByPivot('created_at', 'desc');
    }

    /**
     * The last achievements that user has earned.
     */
    public function lastAchievements(?string $code = null)
    {
        return $this->achievements($code)
            ->wherePivot('is_last', true);
    }

    /**
     * The next achievements in each category that a user can earn.
     */
    public function nextAvailableAchievements(?string $code = null): \Illuminate\Support\Collection|null
    {
        // disable sql restritions
        DB::statement('SET sql_mode=""');

        // find max levels of each category for this user
        $maxLevels = DB::table('users AS u')
            ->select('au.achievement_id')
            ->selectRaw('MAX(level) AS level')
            ->join('achievement_user AS au', 'u.id', 'au.user_id')
            ->where('u.id', $this->id)
            ->groupBy('achievement_id');

        // find the minimum achievement targets in each category
        // that are bigger than this user levels
        return DB::table('achievement_targets AS at')
            ->select('at.achievement_id', 'a.code', 'a.name')
            ->selectRaw('MIN(at.target) AS target')
            ->joinSub($maxLevels, 'ml', function ($q) {
                $q->on('ml.achievement_id', 'at.achievement_id');
                $q->on('ml.level', '<', 'at.target');
            })
            ->join('achievements AS a', 'a.id', 'at.achievement_id')
            ->when($code , function ($q, $code) {
                $q->where('code', $code);
            })
            ->groupBy('at.achievement_id')
            ->get()
            ->map(function ($row) {
                if ($row->target == 1) {
                    // convert plural to singular form by removing s from first word of name
                    $row->title = 'First ' . preg_replace('/^([a-z])+s(\s|$)/i', '$1$2', $row->name);
                } else {
                    $row->title = $row->target . ' ' . $row->name;
                }
                return $row;
            });
    }
}

