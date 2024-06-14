<?php

namespace App\Engine;

use App\Models\Achievement;

class CharacterConversationEngine extends ConversationEngine
{
    public function getStoragePrefix(): string
    {
        return 'character_';
    }

    public function getEngineName(): string
    {
        return 'character';
    }

    protected function getElement(): string
    {
        return 'Character';
    }

    protected function getInitialTopic(): Achievement
    {
        return Achievement::firstWhere('slug', 'basic_profile');
    }

    protected function getPredefinedQuestions(): ?array
    {
        return null;
    }
}
