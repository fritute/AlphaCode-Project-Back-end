<?php

namespace App\Models;

use PDO;
use Exception;

class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    
    public function __construct()
    {
        $this->db = $this->getConnection();
    }
    
    private function getConnection()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $dbConfig = $config['connections'][$config['default']];
        
        try {
            $dsn = "{$dbConfig['driver']}:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
            $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
            
            return $pdo;
        } catch (Exception $e) {
            throw new Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
    
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch();
    }
    
    public function findAll($where = [], $orderBy = null, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $column => $value) {
                $conditions[] = "{$column} = :{$column}";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($where);
        
        return $stmt->fetchAll();
    }
    
    public function create($data)
    {
        $data = $this->filterFillable($data);
        
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data)
    {
        $data = $this->filterFillable($data);
        
        $sets = [];
        foreach ($data as $column => $value) {
            $sets[] = "{$column} = :{$column}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($data);
    }
    
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        
        return $stmt->execute(['id' => $id]);
    }
    
    private function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
}