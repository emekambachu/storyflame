<?php

namespace Database\Factories;

use App\Models\UserAchievement;
use App\Models\UserAchievementSummary;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserAchievementSummaryFactory extends Factory
{
    protected $model = UserAchievementSummary::class;

    public function definition(): array
    {
        return [
            'summary' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
