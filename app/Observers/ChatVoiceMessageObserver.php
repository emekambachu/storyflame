<?php

namespace App\Observers;

use App\Models\ChatVoiceMessage;

class ChatVoiceMessageObserver
{
    public function created(ChatVoiceMessage $chatVoiceMessage): void
    {

    }

    public function updated(ChatVoiceMessage $chatVoiceMessage): void
    {
    }

    public function deleted(ChatVoiceMessage $chatVoiceMessage): void
    {
    }

    public function restored(ChatVoiceMessage $chatVoiceMessage): void
    {
    }
}
