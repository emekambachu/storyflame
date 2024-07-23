<?php

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\Story;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserAchievementFactory extends Factory
{
    protected $model = UserAchievement::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'achievement_id' => Achievement::first()->id,
            'target_type' => 'App\Models\Story',
            'target_id' => Story::factory(),
            'progress' => $this->faker->numberBetween(0, 100),
            'completed_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
