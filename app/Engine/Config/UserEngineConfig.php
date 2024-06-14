<?php

namespace App\Engine\Config;

class UserEngineConfig extends EngineConfig
{
    public const PREDEFINED_QUESTIONS = [
        [
            'question' => 'What do you want to work on today?',
            'title' => 'How is it going {name}?'
        ],
    ];

    public const INITIAL_ACHIEVEMENT_SLUG = 'writer_identity';

    public const ELEMENT_NAME = 'Writer';
}
