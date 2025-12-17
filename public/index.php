<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Carregar variáveis de ambiente
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, '"\'');
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Configurações básicas
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . ($_ENV['CORS_ALLOWED_ORIGINS'] ?? '*'));
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');

// Responder às requisições OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configurar timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'America/Sao_Paulo');

// Configurar exibição de erros
$debug = $_ENV['APP_DEBUG'] ?? 'true';
if ($debug === 'true' || $debug === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Inicializar o roteador
try {
    require_once __DIR__ . '/../app/Routes/web.php';
} catch (Exception $e) {
    http_response_code(500);
    $debug = $_ENV['APP_DEBUG'] ?? 'true';
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => ($debug === 'true' || $debug === true) ? $e->getMessage() : 'Something went wrong',
        'file' => ($debug === 'true' || $debug === true) ? $e->getFile() : null,
        'line' => ($debug === 'true' || $debug === true) ? $e->getLine() : null,
        'trace' => ($debug === 'true' || $debug === true) ? $e->getTraceAsString() : null
    ]);
}