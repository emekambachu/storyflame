<?php

namespace Database\Factories;

use App\Models\DataPoint;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserDataPoint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserDataPointFactory extends Factory
{
    protected $model = UserDataPoint::class;

    public function definition(): array
    {
        return [
            'target_id' => $this->faker->word(),
            'target_type' => $this->faker->word(),
            'data' => $this->faker->words(),
            'summary' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'user_achievement_id' => UserAchievement::factory(),
        ];
    }
}
