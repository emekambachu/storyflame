<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ChatService
{
    public function __construct(
        public readonly TranscriptionService $transcriptionService
    )
    {
    }

    public function createVoiceMessage(UploadedFile $audio, Chat $chat, User $sender)
    {
        $filename = now()->format('Y-m-d') . '-' . uniqid() . '.' . $audio->extension();
        Storage::disk('local')->put('audio/' . $filename, $audio->get());
        $path = storage_path('app/audio/' . $filename);
        $transcription = $this->transcriptionService->transcribe($path);

        /** @var ChatMessage $msg */
        $msg = $chat->chatMessages()->createQuietly([
            'user_id' => $sender->id,
            'content' => $transcription,
        ]);

        $msg->voiceMessage()->create([
            'filename' => $audio->store('tmp'),
        ]);

        return $msg;
    }

    public function create(Chat $chat, User $sender, array $data, array $extra = null)
    {
        if (isset($data['audio'])) {
            return $this->createVoiceMessage($data['audio'], $chat, $sender);
        } else if (isset($data['content'])) {
            return $chat->chatMessages()->create([
                'user_id' => $sender->id,
                'content' => $data['content'],
                'extra_attributes' => $extra ?? null,
            ]);
        } else {
            throw new \InvalidArgumentException('Invalid message data');
        }
    }
}
