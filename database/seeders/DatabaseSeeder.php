<?php

namespace Database\Seeders;

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
        $lessons = Lesson::factory()
            ->count(20)
            ->create();

        Badge::create(['name' => 'Beginner']);
        Badge::create(['name' => 'Intermediate']);
        Badge::create(['name' => 'Advanced']);
        Badge::create(['name' => 'Master']);
    }
}
