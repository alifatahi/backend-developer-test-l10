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
}
