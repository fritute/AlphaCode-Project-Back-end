<?php

// Rota básica para testar a API
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

// Remover o caminho base se estiver em uma subpasta
$base_path = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
if ($base_path !== '/') {
    $request_uri = str_replace($base_path, '', $request_uri);
}

// Roteamento básico
switch ($request_uri) {
    case '/':
    case '/api':
        if ($request_method === 'GET') {
            echo json_encode([
                'message' => 'Bem-vindo à API AlphaCode!',
                'version' => '1.0.0',
                'timestamp' => date('Y-m-d H:i:s'),
                'environment' => $_ENV['APP_ENV'] ?? 'development'
            ]);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
        }
        break;
        
    case '/api/health':
        if ($request_method === 'GET') {
            echo json_encode([
                'status' => 'OK',
                'timestamp' => date('Y-m-d H:i:s'),
                'uptime' => 'Sistema funcionando'
            ]);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode([
            'error' => 'Rota não encontrada',
            'path' => $request_uri,
            'method' => $request_method
        ]);
        break;
}