<?php

return [
    'log_level' => $_ENV['LOG_LEVEL'] ?? 'info',
    'log_file' => __DIR__ . '/../storage/logs/app.log',
    'max_files' => 30,
    'date_format' => 'Y-m-d H:i:s',
    
    'channels' => [
        'file' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../storage/logs/app.log',
            'level' => 'debug',
        ],
        
        'error' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../storage/logs/error.log',
            'level' => 'error',
        ],
        
        'access' => [
            'driver' => 'file',
            'path' => __DIR__ . '/../storage/logs/access.log',
            'level' => 'info',
        ]
    ]
];