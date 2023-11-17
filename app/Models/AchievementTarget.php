<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'achievement_id',
        'target',
    ];

    public function achievement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }
}
