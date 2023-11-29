<?php

namespace App\Listeners;

use App\Models\Badge;
use App\Models\User;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckForNewBadge
{
    /**
     * Create the event listener.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        DB::beginTransaction();
        // find the last badge that user earned
        $lastBadge = $event->user->currentBadge();
        // count user last achievements
        $achievementCount = $event->user->achievements()->count();
        // find the last badge that its level is lowers than or equals to $achievementCount
        $badge = Badge::hasTarget($achievementCount);
        dd($lastBadge->toArray(), $badge->toArray(), $achievementCount);
        // a badge with desired level found and the user badge level is lower than it
        if ($badge && $badge->target > ($lastBadge->target ?? -1)) {
            // remove is_last flag from old badge if exists
            if ($lastBadge) {
                $lastBadge->is_last = false;
                $lastBadge->save();
            }
            // add the new badge to the user
            $event->user->badge()->attach($badge, ['is_last' => true]);
        }

        DB::commit();
    }
}
