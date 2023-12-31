<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadgeTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_id',
        'target',
    ];

    /**
     * Define the relationship to the Badge model.
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

}
