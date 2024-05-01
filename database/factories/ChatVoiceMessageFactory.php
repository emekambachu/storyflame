<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatVoiceMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ChatVoiceMessageFactory extends Factory
{
    protected $model = ChatVoiceMessage::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'filename' => $this->faker->word(),
            'transcription' => $this->faker->word(),

            'chat_message_id' => ChatMessage::factory(),
        ];
    }
}
