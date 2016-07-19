<?php
return [
    'default_alerts' => [
        'min_alert_threshold' => -90,
        'max_alert_threshold' => 90
    ],
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