-- Script SQL para SQLite
-- Criação da tabela de contatos
create database alpha_code_contatos

use alpha_code_contatos;

CREATE TABLE IF NOT EXISTS tbl_contatos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(300) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    profissao VARCHAR(100) NOT NULL,
    telefone_contato VARCHAR(10) NOT NULL,
    celular_contato VARCHAR(11) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Inserir alguns dados de exemplo
INSERT OR IGNORE INTO tbl_contatos (nome, email, data_nascimento, profissao, telefone_contato, celular_contato) VALUES
('João da Silva', 'joao@example.com', '1990-05-15', 'Desenvolvedor', '3333334444', '11999998888'),
('Maria Santos', 'maria@example.com', '1985-03-20', 'Analista de Sistemas', '3333445566', '11987654321'),
('Pedro Costa', 'pedro@example.com', '1988-12-10', 'Designer', '3333556677', '11976543210');