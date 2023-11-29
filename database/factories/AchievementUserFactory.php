<?php

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\AchievementUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AchievementUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'achievement_id' => Achievement::factory(),
        ];
    }
}
