<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
class CheckForNewAchievement implements ShouldQueue
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

        // find the last achievement (matched with $event->code) that user earned
        $lastAchievement = $event->user->lastAchievements(code: $event->code)->get()->last();
        // Find the last achievement that its level is lowers than or equals to $event->count
        $achievement = Achievement::hasTarget($event->count, $event->code);
        // do nothings if an achievement with desired level not found or the user achievement level is greater or equal to it
        if (!$achievement || $achievement->target <= ($lastAchievement->level ?? -1)) {
            return;
        }
        // remove is_last flag from old achievement if exists
        if ($lastAchievement) {
            $lastAchievement->is_last = false;
            $lastAchievement->save();
        }

        if ($achievement->target == 1) {
            // convert plural to singular form by removing s from first word of name
            $title = 'First ' . preg_replace('/^([a-z]+)s(\s|$)/i', '$1$2', $achievement->name);
        } else {
            $title = $achievement->target . ' ' . $achievement->name;
        }
        // Add the new achievement to the user
        $payload = [
            'level' => $achievement->target,
            'title' => $title,
            'is_last' => true,
        ];
        $event->user->achievements()->attach($achievement, $payload);

        // Trigger the AchievementUnlocked event to check if a new badge can be earned.
        event(new AchievementUnlocked($event->user));

        DB::commit();
    }
}
