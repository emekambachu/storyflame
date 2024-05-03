<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class ChatService
{
    public function createVoiceMessage(UploadedFile $audio, Chat $chat, User $sender)
    {
        /** @var ChatMessage $msg */
        $msg = $chat->chatMessages()->createQuietly([
            'user_id' => $sender->id,
            'content' => null,
        ]);

        $msg->voiceMessage()->create([
            'filename' => $audio->store('tmp'),
        ]);

        return $msg;
    }

    public function create(Chat $chat, User $sender, array $data)
    {
        if (isset($data['audio'])) {
            return $this->createVoiceMessage($data['audio'], $chat, $sender);
        } else if (isset($data['content'])) {
            return $chat->chatMessages()->create([
                'user_id' => $sender->id,
                'content' => $data['content'],
            ]);
        } else {
            throw new \InvalidArgumentException('Invalid message data');
        }
    }
}
