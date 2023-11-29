<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Listeners\CheckCommentWriteAchievement;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CheckCommentWriteAchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_comment_write_achievement_listener()
    {
        // Arrange
        $user = User::factory()->create();
        $achievement = Achievement::factory()->create([
            'name' => 'Comments Written',
            'code' => Achievement::COMMENT_WRITE_CODE,
        ]);

        // write three comment and assert for achievement only in third comment
        for ($i=0; $i<3; $i++) {
            // comment write
            $comment = Comment::factory()->create([
                'user_id' => $user->id,
            ]);
            Event::fake(CommentWritten::class);
            if ($i < 2) {
                $this->assertDatabaseCount('comments', $i + 1);
                $this->assertDatabaseEmpty('achievement_user');
            } else {
                $this->assertDatabaseCount('achievement_user', 1);
                // $this->assertDatabaseHas('achievement_user', [
                //     'user_id' => $user->id,
                //     'achievement_id' => $achievement->id,
                //     'is_last' => true,
                //     'level' => $achievement->level,
                // ]);
            }
        }

        // Mock the AchievementUnlocked event
        Event::fake(AchievementUnlocked::class);

        // // Act
        // $listener = new CheckCommentWriteAchievement($user);
        // $listener->handle(new \stdClass()); // Pass a dummy event, as it's not used in the listener

        // // Assert
        // // $this->assertDatabaseCount('achievements', 1); // Ensure one achievement record is created for the user
        // $this->assertDatabaseHas('achievement_user', [
        //     'user_id' => $user->id,
        //     'achievement_id' => $achievement->id,
        //     'is_last' => true,
        //     'level' => $achievement->level,
        // ]);

        // Event::assertDispatched(AchievementUnlocked::class, function ($event) use ($achievement, $user) {
        //     return $event->achievement->id === $achievement->id && $event->user->id === $user->id;
        // });
    }
}
