<?php

namespace App\Engine\Config;

abstract class EngineConfig
{
    public const ALLOW_ENGINE_SWITCH = true;
    /**
     * @var string
     */
    public const ELEMENT_NAME = '';
    /**
     * @var string
     */
    public const INITIAL_ACHIEVEMENT_SLUG = '';

    /**
     * @var array<string>|array<string, array<string, string>>
     */
    public const PREDEFINED_QUESTIONS = [];
    public const ALLOW_ACHIEVEMENT_SWITCH = true;
}
