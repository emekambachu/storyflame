<?php

namespace Database\Factories;

use App\Models\Story;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StoryFactory extends Factory
{
    protected $model = Story::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => $this->faker->sentence(),
        ];
    }
}
