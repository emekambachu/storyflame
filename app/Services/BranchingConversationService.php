<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;

abstract class BranchingConversationService extends ConversationService
{
    protected const EXTRACTION_URL = '/conversation/extract';

    public function getFirstBranch(): string
    {
        return array_key_first(static::QUESTION_GROUPS);
    }

    public function getCurrentBranch(?Chat $chat)
    {
        return $chat?->chatMessages()->latest()?->first()->branch ?? $this->getFirstBranch();
    }

    protected function saveAnswerData(ChatMessage $answer, array $extracted): void
    {
        dd($extracted);
    }
}
