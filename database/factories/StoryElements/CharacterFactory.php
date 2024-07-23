<?php

namespace Database\Factories\StoryElements;

use App\Models\Story;
use App\Models\StoryElements\Character;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CharacterFactory extends Factory
{
    protected $model = Character::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'story_id' => Story::factory(),
        ];
    }
}
