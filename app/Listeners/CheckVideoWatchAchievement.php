<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckVideoWatchAchievement implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        DB::beginTransaction();

        // find the last VIDEO_WATCH achievement that user earned
        $lastVideoWatchAchievement = $event->user->achievements(code: Achievement::VIDEO_WATCH_CODE)->last();
        // count user videoWatches
        $videoWatchCount = $event->user->videoWatch()->count();
        // Find the last achievement that its level is lowers than or equals to $videoWatchCount
        $achievement = Achievement::hasTarget($videoWatchCount, Achievement::VIDEO_WATCH_CODE);

        // An achievement with desired level found and the user achievement level is lower than it
        if ($achievement && $achievement->level > ($lastVideoWatchAchievement->level ?? -1)) {
            // remove is_last flag from old achievement if exists
            if ($lastVideoWatchAchievement) {
                $lastVideoWatchAchievement->isLast = false;
                $lastVideoWatchAchievement->save();
            }

            if ($achievement->level == 1) {
                // convert plural to singular form by removing s from first word of name
                $title = 'First ' . preg_replace('/^([a-z]+)s(\s|$)/i', '$1$2', $achievement->name);
            } else {
                $title = $achievement->level . ' ' . $achievement->name;
            }
            // Add the new achievement to the user
            $event->user->achievements()->attach($achievement, [
                'level' => $achievement->level,
                'title' => $title,
                'is_last' => true,
            ]);

            // Trigger the AchievementUnlocked event to check if a new badge can be earned.
            event(new AchievementUnlocked($event->user));
        }

        DB::commit();
    }
}
