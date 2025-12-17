<?php
/**
 * Script para criar o banco SQLite e a tabela de contatos
 */

try {
    // Caminho para o banco SQLite
    $dbPath = __DIR__ . '/../storage/database.sqlite';
    
    // Criar diretório se não existir
    $storageDir = dirname($dbPath);
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0755, true);
    }
    
    // Conectar ao SQLite
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL para criar a tabela
    $sql = "
    CREATE TABLE IF NOT EXISTS tbl_contatos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(300) NOT NULL UNIQUE,
        data_nascimento DATE NOT NULL,
        profissao VARCHAR(100) NOT NULL,
        telefone_contato VARCHAR(8) NOT NULL,
        celular_contato VARCHAR(11) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ";
    
    $db->exec($sql);
    
    // Inserir dados de exemplo
    $insertSql = "
    INSERT OR IGNORE INTO tbl_contatos (nome, email, data_nascimento, profissao, telefone_contato, celular_contato) VALUES
    ('João da Silva', 'joao@example.com', '1990-05-15', 'Desenvolvedor', '33334444', '11999998888'),
    ('Maria Santos', 'maria@example.com', '1985-03-20', 'Analista de Sistemas', '33445566', '11987654321'),
    ('Pedro Costa', 'pedro@example.com', '1988-12-10', 'Designer', '33556677', '11976543210');
    ";
    
    $db->exec($insertSql);
    
    echo "✅ Banco SQLite criado com sucesso!\n";
    echo "📁 Localização: " . $dbPath . "\n";
    echo "📊 Tabela 'tbl_contatos' criada\n";
    echo "🎯 Dados de exemplo inseridos\n";
    
} catch (PDOException $e) {
    echo "❌ Erro ao criar banco SQLite: " . $e->getMessage() . "\n";
}