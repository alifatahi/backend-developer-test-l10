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

    /**
     * Define the relationship to the Achievement model.
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
