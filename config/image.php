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
];
