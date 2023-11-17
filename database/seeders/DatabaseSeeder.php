<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\AchievementTarget;
use App\Models\Badge;
use App\Models\BadgeTarget;
use App\Models\Lesson;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // lista of each badge and its target
    protected $badgeList = [
        'Beginner' => 0,
        'Intermediate' => 4,
        'Advanced' => 8,
        'Master' => 10,
    ];

    // list of each achievemnet and its target and code
    protected $achievementList = [
        [
            'name' => 'Lessons Watched',
            'code' => 'lesson-watch',
            'target_list' => [ 1, 5, 10, 25, 50 ],
        ],
        [
            'name' => 'Comments Written',
            'code' => 'comment-write',
            'target_list' => [ 1, 3, 5, 10, 20 ],
        ],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Lessons
        $lessons = Lesson::factory()
            ->count(20)
            ->create();

        // Badges
        foreach ($this->badgeList as $badgeName => $target) {
            // create badge and get the instance
            $badge = Badge::create(['name' => $badgeName]);
            // create each badge target
            BadgeTarget::create([
                'badge_id' => $badge->id,
                'target'=> $target,
            ]);
        }

        // Achievements
        foreach ($this->achievementList as $achievementInfo) {
            // create achievement and get the instance
            $achievement = Achievement::create([
                'name' => $achievementInfo['name'],
                'code' => $achievementInfo['code'],
            ]);
            // create each achievement targets
            foreach ($achievementInfo['target_list'] as $target) {
                AchievementTarget::create([
                    'achievement_id' => $achievement->id,
                    'target'=> $target,
                ]);
            }
        }
    }
}
