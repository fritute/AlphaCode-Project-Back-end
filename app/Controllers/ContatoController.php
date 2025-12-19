<?php

namespace App\Controllers;

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/ContatoModel.php';

use App\Models\ContatoModel;
use Exception;

class ContatoController extends BaseController
{
    private $contatoModel;
    
    public function __construct()
    {
        $this->contatoModel = new ContatoModel();
    }
    
    /**
     * GET /contatos - Listar todos os contatos
     */
    public function index()
    {
        try {
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 10;
            $search = $_GET['search'] ?? '';
            
            $where = [];
            if (!empty($search)) {
                // Para busca simples, você pode usar o método findByName
                $contatos = $this->contatoModel->findByName($search);
            } else {
                $contatos = $this->contatoModel->findAll();
            }
            
            $total = $this->contatoModel->count();
            
            $this->successResponse('Contatos listados com sucesso', [
                'contatos' => $contatos,
                'total' => $total,
                'page' => (int) $page,
                'limit' => (int) $limit
            ]);
            
        } catch (Exception $e) {
            $this->errorResponse('Erro ao listar contatos: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * GET /contatos/{id} - Buscar contato por ID
     */
    public function show($id)
    {
        try {
            if (empty($id)) {
                $this->errorResponse('ID do contato é obrigatório', 400);
            }
            
            $contato = $this->contatoModel->find($id);
            
            if (!$contato) {
                $this->errorResponse('Contato não encontrado', 404);
            }
            
            $this->successResponse('Contato encontrado', $contato);
            
        } catch (Exception $e) {
            $this->errorResponse('Erro ao buscar contato: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * POST /contatos - Criar novo contato
     */
    public function store()
    {
        try {
            $data = $this->getRequestData();
            
            // Validação básica
            $errors = $this->validateContactData($data);
            if (!empty($errors)) {
                $this->errorResponse('Dados inválidos', 422, $errors);
            }
            
            // Verificar se email já existe
            if ($this->contatoModel->emailExists($data['email'])) {
                $this->errorResponse('Email já cadastrado', 409);
            }
            
            $id = $this->contatoModel->create($data);
            $contato = $this->contatoModel->find($id);
            
            $this->successResponse('Contato criado com sucesso', $contato, 201);
            
        } catch (Exception $e) {
            $this->errorResponse('Erro ao criar contato: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * PUT /contatos/{id} - Atualizar contato
     */
    public function update($id)
    {
        try {
            if (empty($id)) {
                $this->errorResponse('ID do contato é obrigatório', 400);
            }
            
            $contato = $this->contatoModel->find($id);
            if (!$contato) {
                $this->errorResponse('Contato não encontrado', 404);
            }
            
            $data = $this->getRequestData();
            
            // Validação básica
            $errors = $this->validateContactData($data, false);
            if (!empty($errors)) {
                $this->errorResponse('Dados inválidos', 422, $errors);
            }
            
            // Verificar se email já existe (excluindo o próprio contato)
            if (isset($data['email']) && $this->contatoModel->emailExists($data['email'], $id)) {
                $this->errorResponse('Email já cadastrado por outro contato', 409);
            }
            
            $this->contatoModel->update($id, $data);
            $contatoAtualizado = $this->contatoModel->find($id);
            
            $this->successResponse('Contato atualizado com sucesso', $contatoAtualizado);
            
        } catch (Exception $e) {
            $this->errorResponse('Erro ao atualizar contato: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * DELETE /contatos/{id} - Excluir contato
     */
    public function delete($id)
    {
        try {
            if (empty($id)) {
                $this->errorResponse('ID do contato é obrigatório', 400);
            }
            
            $contato = $this->contatoModel->find($id);
            if (!$contato) {
                $this->errorResponse('Contato não encontrado', 404);
            }
            
            $this->contatoModel->delete($id);
            
            $this->successResponse('Contato excluído com sucesso');
            
        } catch (Exception $e) {
            $this->errorResponse('Erro ao excluir contato: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * GET /contatos/profissao/{profissao} - Buscar contatos por profissão
     */
    public function byProfissao($profissao)
    {
        try {
            if (empty($profissao)) {
                $this->errorResponse('Nome da profissão é obrigatório', 400);
            }
            
            $contatos = $this->contatoModel->findByProfissao($profissao);
            
            $this->successResponse('Contatos encontrados', $contatos);
            
        } catch (Exception $e) {
            $this->errorResponse('Erro ao buscar contatos: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * GET /contatos/ano/{ano} - Buscar contatos por ano de nascimento
     */
    public function byAnoNascimento($ano)
    {
        try {
            if (empty($ano) || !is_numeric($ano)) {
                $this->errorResponse('Ano deve ser um número válido', 400);
            }
            
            $contatos = $this->contatoModel->findByAnoNascimento($ano);
            
            $this->successResponse('Contatos encontrados', $contatos);
            
        } catch (Exception $e) {
            $this->errorResponse('Erro ao buscar contatos: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Validar dados do contato
     */
    private function validateContactData($data, $isRequired = true)
    {
        $errors = [];
        
        // Nome
        if ($isRequired && empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório';
        } elseif (isset($data['nome']) && strlen($data['nome']) < 2) {
            $errors['nome'] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        // Email
        if ($isRequired && empty($data['email'])) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }
        
        // Data de nascimento
        if ($isRequired && empty($data['data_nascimento'])) {
            $errors['data_nascimento'] = 'Data de nascimento é obrigatória';
        } elseif (isset($data['data_nascimento']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['data_nascimento'])) {
            $errors['data_nascimento'] = 'Data de nascimento deve estar no formato YYYY-MM-DD';
        }
        
        // Profissão
        if ($isRequired && empty($data['profissao'])) {
            $errors['profissao'] = 'Profissão é obrigatória';
        }
        
        // Telefone contato (10 dígitos)
        if ($isRequired && empty($data['telefone_contato'])) {
            $errors['telefone_contato'] = 'Telefone de contato é obrigatório';
        } elseif (isset($data['telefone_contato']) && !preg_match('/^\d{10}$/', $data['telefone_contato'])) {
            $errors['telefone_contato'] = 'Telefone deve ter exatamente 10 dígitos';
        }
        
        // Celular contato (11 dígitos)
        if ($isRequired && empty($data['celular_contato'])) {
            $errors['celular_contato'] = 'Celular de contato é obrigatório';
        } elseif (isset($data['celular_contato']) && !preg_match('/^\d{11}$/', $data['celular_contato'])) {
            $errors['celular_contato'] = 'Celular deve ter exatamente 11 dígitos';
        }
        
        return $errors;
    }
}