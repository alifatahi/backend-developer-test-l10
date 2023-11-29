<?php

namespace App\Events;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class LessonWatched
{
    use Dispatchable, SerializesModels;

    // Achievement Code
    public $code;
    // number of lessons that user has been watched
    public $count;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public User $user,
    ) {
        $this->code = Achievement::VIDEO_WATCH_CODE;
        $this->count = $user->watchedLessons()->count();
    }
}
