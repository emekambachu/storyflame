<?php

namespace Database\Factories\DataPoint;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DataPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug,
            'name' => $this->faker->name,
            'type' => $this->faker->word,
            'extraction_description' => $this->faker->text,
            'example' => $this->faker->words,
            'purpose' => $this->faker->text,
            'development_order' => $this->faker->randomNumber(),
            'impact_score' => $this->faker->randomNumber(),
        ];
    }
}
