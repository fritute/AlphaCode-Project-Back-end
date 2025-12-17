<?php

require_once __DIR__ . '/../Controllers/ContatoController.php';

use App\Controllers\ContatoController;

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
        
    // Rotas para CRUD de Contatos
    case '/api/contatos':
        $contatoController = new ContatoController();
        switch ($request_method) {
            case 'GET':
                $contatoController->index();
                break;
            case 'POST':
                $contatoController->store();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
        }
        break;
        
    case (preg_match('/^\/api\/contatos\/(\d+)$/', $request_uri, $matches) ? true : false):
        $contatoController = new ContatoController();
        $id = $matches[1];
        switch ($request_method) {
            case 'GET':
                $contatoController->show($id);
                break;
            case 'PUT':
                $contatoController->update($id);
                break;
            case 'DELETE':
                $contatoController->delete($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
        }
        break;
        
    case (preg_match('/^\/api\/contatos\/profissao\/(.+)$/', $request_uri, $matches) ? true : false):
        $contatoController = new ContatoController();
        $profissao = urldecode($matches[1]);
        if ($request_method === 'GET') {
            $contatoController->byProfissao($profissao);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
        }
        break;
        
    case (preg_match('/^\/api\/contatos\/ano\/(\d{4})$/', $request_uri, $matches) ? true : false):
        $contatoController = new ContatoController();
        $ano = $matches[1];
        if ($request_method === 'GET') {
            $contatoController->byAnoNascimento($ano);
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
            'method' => $request_method,
            'available_routes' => [
                'GET /api' => 'Informações da API',
                'GET /api/health' => 'Status da API',
                'GET /api/contatos' => 'Listar contatos',
                'POST /api/contatos' => 'Criar contato',
                'GET /api/contatos/{id}' => 'Buscar contato por ID',
                'PUT /api/contatos/{id}' => 'Atualizar contato',
                'DELETE /api/contatos/{id}' => 'Excluir contato',
                'GET /api/contatos/profissao/{profissao}' => 'Buscar contatos por profissão',
                'GET /api/contatos/ano/{ano}' => 'Buscar contatos por ano de nascimento'
            ]
        ]);
        break;
}