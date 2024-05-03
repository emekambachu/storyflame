<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => $this->faker->randomElement(['onboarding', 'support']),

            'recipient_id' => User::factory(),
            'sender_id' => User::factory()
        ];
    }
}
