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


    public function badge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }

}
