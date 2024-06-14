<?php

namespace App\Engine\Config;

class CharacterEngineConfig extends EngineConfig
{
    public const PREDEFINED_QUESTIONS = [
        [
            'question' => 'Tell me more about your character.',
            'title' => 'Outlining a character'
        ]
    ];

    public const ELEMENT_NAME = 'Character';

    public const INITIAL_ACHIEVEMENT_SLUG = 'basic_profile';
}
