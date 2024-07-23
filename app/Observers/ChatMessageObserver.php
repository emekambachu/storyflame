<?php

namespace App\Observers;

use App\Models\Chat\SessionChat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\FreeTrialService;
use Illuminate\Support\Facades\Log;

class ChatMessageObserver
{
    public function creating(ChatMessage $chatMessage): void
    {

    }

    public function created(ChatMessage $chatMessage): void
    {
        if ($chatMessage->is_system) {
            if ($chatMessage->content === 'finish') {
                Log::info('Got finish system message, finishing sessions', ['chat_message_id' => $chatMessage->id]);
                $chatMessage->chat->sessionChats->each(
                    function (SessionChat $sessionChat) {
                        Log::info('Finishing session chat', ['session_chat_id' => $sessionChat->id]);
                        $sessionChat->finish();
                    }
                );
            }
        }
        if($chatMessage->is_user){
            $freeTrialService = new FreeTrialService();
            $freeTrialService->trackInteraction($chatMessage->user);
        }
    }

    public function updated(ChatMessage $chatMessage): void
    {
    }

    public function deleted(ChatMessage $chatMessage): void
    {
    }

    public function restored(ChatMessage $chatMessage): void
    {
    }
}
