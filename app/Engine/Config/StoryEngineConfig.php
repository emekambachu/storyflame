<?php

namespace App\Engine\Config;

use App\Engine\Config\EngineConfig;

class StoryEngineConfig extends EngineConfig
{
    public const PREDEFINED_QUESTIONS = [
        [
            'question' => 'What is your story about?',
            'title' => "Let's start with your story!"
        ]
    ];

    public const ELEMENT_NAME = 'Story';

    public const INITIAL_ACHIEVEMENT_SLUG = 'story_fundamentals';
}
