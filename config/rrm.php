<?php
return [
    'filesystem' => [
        'logo' => [
            'default' => 'default-logo.png',
            'directory' => storage_path() . '/logos/',
            'filesystem' => env('LOGO_FILESYSTEM', 'local'),
            'max_width' => 200,
            'max_height' => 100
        ]
    ]
];