<?php

namespace App\Engine\Config;

class OnboardingEngineConfig extends EngineConfig
{
    public const ALLOW_ENGINE_SWITCH = false;
    public const PREDEFINED_QUESTIONS = [
        [
            'question' => 'What is your name?',
            'title' => 'Hello, welcome to {app_name}!',
            'data_points' => [
                'name'
            ]
        ],
        [
            'question' => 'Tell us about yourself as a writer.',
            'title' => 'Nice to meet up, {extracted.writer_name}!',
            'data_points' => [
                'writing_motivation' // todo: not sure if this is the right data point, but it's only maters if we generate follow-up or brainstorming questions
            ]
        ],
    ];

    // TODO: when the onboarding answers are all filled, the backend should send something to the frontend to show the Development header with "finish onboarding" button
    // TODO: implement the PREDEFINED_CLOSING_QUESTION into the engine

    public const PREDEFINED_CLOSING_QUESTION = [
        [
            'question' => "What story do you want to start developing?",
            'title' => "Great, we'll build your profile.",
            'data_points' => []
        ]
    ];

    public const INITIAL_ACHIEVEMENT_SLUG = 'writer_identity';

    public const ELEMENT_NAME = 'Writer';

    public const ALLOW_ACHIEVEMENT_SWITCH = false;

    public const ENDPOINT_KEY = 'onboarding'; // not used anymore

    public const SESSION_CHAT_PERSISTENT = true;
}
