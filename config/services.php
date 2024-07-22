<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'tmdb' => [
        'token' => env('TMDB_TOKEN'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'leonardo' => [
        'api_key' => env('LEONARDO_API_KEY'),
        'webhook_token' => env('LEONARDO_WEBHOOK_TOKEN'),
        'prompt_settings' => [
            'alchemy' => false,
            'modelId' => 'b24e16ff-06e3-43eb-8d33-4416c2d75876',
            'num_images' => 1,
            'public' => false,
        ]
    ],

];
