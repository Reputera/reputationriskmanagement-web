<?php
return [
    'filesystem' => [
        'logo' => [
            'directory' => 'logos',
            'filesystem' => env('LOGO_FILESYSTEM', 'local'),
            'max_width' => 200,
            'max_height' => 100
        ]
    ]
];