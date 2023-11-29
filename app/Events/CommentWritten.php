<?php

namespace App\Events;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CommentWritten
{
    use Dispatchable, SerializesModels;

    // Achievement Code
    public $code;
    // number of comments that user has been written
    public $count;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public User $user,
    ) {
        $this->code = Achievement::COMMENT_WRITE_CODE;
        $this->count = $user->comments()->count();
    }
}
