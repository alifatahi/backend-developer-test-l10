<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Lesson;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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
        Badge::create(['name' => 'Beginner']);
        Badge::create(['name' => 'Intermediate']);
        Badge::create(['name' => 'Advanced']);
        Badge::create(['name' => 'Master']);

        // Achivements
        Achievement::create([
            'name' => 'Lessons Watched',
            'code' => 'lesson-watch',
        ]);
        Achievement::create([
            'name' => 'Comments Written',
            'code' => 'comment-write',
        ]);

        // Badge Targets

        // Achivement Targets
    }
}
