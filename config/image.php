<?php

return [
    'sizes' => [
        \App\Models\ImageFile::ORIGINAL => [
            'width' => 0,
            'height' => 0,
            'quality' => 100,
        ],
        \App\Models\ImageFile::SIZE_THUMBNAIL => [
            'width' => 100,
            'height' => 100,
            'quality' => 40,
        ],
        \App\Models\ImageFile::SIZE_SMALL => [
            'width' => 300,
            'height' => 300,
            'quality' => 50,
        ],
        \App\Models\ImageFile::SIZE_MEDIUM => [
            'width' => 600,
            'height' => 600,
            'quality' => 50,
        ],
        \App\Models\ImageFile::SIZE_LARGE => [
            'width' => 1200,
            'height' => 1200,
            'quality' => 60,
        ],
    ],
    'valid_mimes' => [
        'jpeg',
        'jpg',
        'png',
        'gif',
    ],
    'max_file_size' => 3 * 1024,
    'leonardo_settings' => [
        'api_key' => env('LEONARDO_API_KEY'),
        'callback_api_key' => env('LEONARDO_API_CALLBACK_API_KEY'),
        'prompt_settings' => [
            'alchemy' => false,
            'modelId' => 'b24e16ff-06e3-43eb-8d33-4416c2d75876',
            'num_images' => 1,
            'public' => false,
        ]
    ],
];
