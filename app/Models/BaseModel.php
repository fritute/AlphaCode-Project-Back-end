<?php

namespace App\Models;

use Exception;

class BaseModel
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $dataFile;
    
    public function __construct()
    {
        $this->dataFile = $this->getDataFilePath();
        $this->ensureDataFileExists();
    }
    
    private function getDataFilePath()
    {
        $storageDir = __DIR__ . '/../../storage/data';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        return $storageDir . '/' . $this->table . '.json';
    }
    
    private function ensureDataFileExists()
    {
        if (!file_exists($this->dataFile)) {
            // Criar arquivo com dados de exemplo se for a tabela de contatos
            if ($this->table === 'tbl_contatos') {
                $sampleData = [
                    [
                        'id' => 1,
                        'nome' => 'João da Silva',
                        'email' => 'joao@example.com',
                        'data_nascimento' => '1990-05-15',
                        'profissao' => 'Desenvolvedor',
                        'telefone_contato' => '33334444',
                        'celular_contato' => '11999998888',
                        'created_at' => '2025-12-17 10:00:00',
                        'updated_at' => '2025-12-17 10:00:00'
                    ],
                    [
                        'id' => 2,
                        'nome' => 'Maria Santos',
                        'email' => 'maria@example.com',
                        'data_nascimento' => '1985-03-20',
                        'profissao' => 'Analista de Sistemas',
                        'telefone_contato' => '33445566',
                        'celular_contato' => '11987654321',
                        'created_at' => '2025-12-17 10:00:00',
                        'updated_at' => '2025-12-17 10:00:00'
                    ]
                ];
                file_put_contents($this->dataFile, json_encode($sampleData, JSON_PRETTY_PRINT));
            } else {
                file_put_contents($this->dataFile, json_encode([]));
            }
        }
    }
    
    private function readData()
    {
        $content = file_get_contents($this->dataFile);
        return json_decode($content, true) ?: [];
    }
    
    private function writeData($data)
    {
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT));
    }
    
    private function getNextId($data)
    {
        if (empty($data)) {
            return 1;
        }
        $maxId = max(array_column($data, $this->primaryKey));
        return $maxId + 1;
    }
    
    public function find($id)
    {
        $data = $this->readData();
        foreach ($data as $record) {
            if ($record[$this->primaryKey] == $id) {
                return $record;
            }
        }
        return null;
    }
    
    public function findAll($where = [], $orderBy = null, $limit = null)
    {
        $data = $this->readData();
        
        // Aplicar filtros WHERE se fornecidos
        if (!empty($where)) {
            $data = array_filter($data, function($record) use ($where) {
                foreach ($where as $field => $value) {
                    if (!isset($record[$field]) || $record[$field] != $value) {
                        return false;
                    }
                }
                return true;
            });
        }
        
        // Aplicar ordenação se fornecida
        if ($orderBy) {
            usort($data, function($a, $b) use ($orderBy) {
                return $a[$orderBy] <=> $b[$orderBy];
            });
        }
        
        // Aplicar limite se fornecido
        if ($limit) {
            $data = array_slice($data, 0, $limit);
        }
        
        return $data;
    }
    
    public function create($attributes)
    {
        $data = $this->readData();
        
        // Filtrar apenas campos permitidos
        $filteredAttributes = [];
        foreach ($this->fillable as $field) {
            if (isset($attributes[$field])) {
                $filteredAttributes[$field] = $attributes[$field];
            }
        }
        
        // Adicionar ID e timestamps
        $filteredAttributes[$this->primaryKey] = $this->getNextId($data);
        $filteredAttributes['created_at'] = date('Y-m-d H:i:s');
        $filteredAttributes['updated_at'] = date('Y-m-d H:i:s');
        
        $data[] = $filteredAttributes;
        $this->writeData($data);
        
        return $filteredAttributes[$this->primaryKey];
    }
    
    public function update($id, $attributes)
    {
        $data = $this->readData();
        
        foreach ($data as $key => $record) {
            if ($record[$this->primaryKey] == $id) {
                // Filtrar apenas campos permitidos
                foreach ($this->fillable as $field) {
                    if (isset($attributes[$field])) {
                        $data[$key][$field] = $attributes[$field];
                    }
                }
                
                $data[$key]['updated_at'] = date('Y-m-d H:i:s');
                $this->writeData($data);
                
                return true;
            }
        }
        
        return false;
    }
    
    public function delete($id)
    {
        $data = $this->readData();
        
        foreach ($data as $key => $record) {
            if ($record[$this->primaryKey] == $id) {
                unset($data[$key]);
                $this->writeData(array_values($data)); // Reindexar array
                return true;
            }
        }
        
        return false;
    }
    
    public function where($field, $value)
    {
        $data = $this->readData();
        $results = [];
        
        foreach ($data as $record) {
            if (isset($record[$field]) && $record[$field] == $value) {
                $results[] = $record;
            }
        }
        
        return $results;
    }
    
    public function whereLike($field, $value)
    {
        $data = $this->readData();
        $results = [];
        
        foreach ($data as $record) {
            if (isset($record[$field]) && stripos($record[$field], $value) !== false) {
                $results[] = $record;
            }
        }
        
        return $results;
    }
    
    private function filterFillable($data)
    {
        $filtered = [];
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $filtered[$field] = $data[$field];
            }
        }
        return $filtered;
    }
}