<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'AlphaCode API',
    'env' => $_ENV['APP_ENV'] ?? 'development',
    'debug' => $_ENV['APP_DEBUG'] ?? true,
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'America/Sao_Paulo',
    
    'key' => $_ENV['APP_KEY'] ?? 'your-secret-key-here',
    
    'cors' => [
        'allowed_origins' => explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? 'http://localhost:3000,http://localhost:5173'),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
        'allow_credentials' => true,
    ],
    
    'jwt' => [
        'secret' => $_ENV['JWT_SECRET'] ?? 'your-jwt-secret-key',
        'algorithm' => 'HS256',
        'expiration' => 3600, // 1 hour
        'refresh_expiration' => 604800, // 7 days
    ],
    
    'api' => [
        'version' => '1.0.0',
        'rate_limit' => 100, // requests per minute
    ]
];