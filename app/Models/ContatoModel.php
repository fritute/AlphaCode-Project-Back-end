<?php

namespace App\Models;

require_once __DIR__ . '/BaseModel.php';

class ContatoModel extends BaseModel
{
    protected $table = 'tbl_contatos';
    protected $primaryKey = 'id';
    protected $fillable = ['nome', 'email', 'data_nascimento', 'profissao', 'telefone_contato', 'celular_contato'];
    
    /**
     * Buscar contatos por nome
     */
    public function findByName($nome)
    {
        return $this->whereLike('nome', $nome);
    }
    
    /**
     * Buscar contatos por email
     */
    public function findByEmail($email)
    {
        $results = $this->where('email', $email);
        return !empty($results) ? $results[0] : null;
    }
    
    /**
     * Buscar contatos por profissão
     */
    public function findByProfissao($profissao)
    {
        return $this->whereLike('profissao', $profissao);
        $stmt->execute(['profissao' => "%{$profissao}%"]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar contatos por data de nascimento (ano)
     */
    public function findByAnoNascimento($ano)
    {
        $data = $this->readData();
        $results = [];
        
        foreach ($data as $record) {
            if (isset($record['data_nascimento'])) {
                $recordYear = date('Y', strtotime($record['data_nascimento']));
                if ($recordYear == $ano) {
                    $results[] = $record;
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Buscar contatos por celular
     */
    public function findByCelular($celular)
    {
        $results = $this->where('celular_contato', $celular);
        return !empty($results) ? $results[0] : null;
    }
    
    /**
     * Contar total de contatos
     */
    public function count()
    {
        $data = $this->readData();
        return count($data);
    }
    
    /**
     * Validar se email já existe
     */
    public function emailExists($email, $excludeId = null)
    {
        $data = $this->readData();
        
        foreach ($data as $record) {
            if ($record['email'] == $email && ($excludeId === null || $record['id'] != $excludeId)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Método para acessar dados (usado por alguns métodos que precisam acessar diretamente)
     */
    protected function readData()
    {
        $content = file_get_contents($this->dataFile);
        return json_decode($content, true) ?: [];
    }
}