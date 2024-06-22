<?php

namespace Database\Factories\Summary;

use App\Services\Base\BaseService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SummaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'slug' => $this->faker->slug,
            'item_id' => (int)BaseService::randomCharacters(5, '0123456789'),
            'location' => $this->faker->address,
            'purpose' => $this->faker->sentence,
            'creation_prompt' => $this->faker->sentence,
            'example_summary' => $this->faker->sentence,
            'published_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
