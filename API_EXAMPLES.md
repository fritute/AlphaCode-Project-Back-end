# CRUD API - Exemplos de Contatos

## Configuração do Banco de Dados

1. Execute o script SQL em `config/database.sql` para criar a tabela `tbl_contatos`
2. Configure as variáveis de ambiente no seu arquivo `.env` ou ajuste diretamente em `config/database.php`

## Estrutura da Tabela
```sql
CREATE TABLE tbl_contatos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome varchar(100) NOT NULL,
    email varchar(300) NOT NULL,
    data_nascimento date NOT NULL,
    profissao varchar(100) NOT NULL,
    telefone_contato varchar(8) NOT NULL,
    celular_contato varchar(11) NOT NULL
);
```

## Endpoints Disponíveis

### 1. Listar todos os contatos
```http
GET /api/contatos
```

**Parâmetros opcionais:**
- `page`: Número da página (padrão: 1)
- `limit`: Limite por página (padrão: 10)  
- `search`: Buscar por nome

**Exemplo:**
```http
GET /api/contatos?search=João&limit=5
```

### 2. Buscar contato por ID
```http
GET /api/contatos/{id}
```

**Exemplo:**
```http
GET /api/contatos/1
```

### 3. Criar novo contato
```http
POST /api/contatos
Content-Type: application/json

{
    "nome": "João da Silva",
    "email": "joao@example.com",
    "data_nascimento": "1990-05-15",
    "profissao": "Desenvolvedor",
    "telefone_contato": "33334444",
    "celular_contato": "11999998888"
}
```

### 4. Atualizar contato
```http
PUT /api/contatos/{id}
Content-Type: application/json

{
    "nome": "João Silva Santos",
    "celular_contato": "11888887777"
}
```

### 5. Excluir contato
```http
DELETE /api/contatos/{id}
```

### 6. Buscar contatos por profissão
```http
GET /api/contatos/profissao/{profissao}
```

**Exemplo:**
```http
GET /api/contatos/profissao/Desenvolvedor
```

### 7. Buscar contatos por ano de nascimento
```http
GET /api/contatos/ano/{ano}
```

**Exemplo:**
```http
GET /api/contatos/ano/1990
```

## Validações

### Campos obrigatórios (POST):
- `nome`: Mínimo 2 caracteres
- `email`: Formato válido e único  
- `data_nascimento`: Formato YYYY-MM-DD
- `profissao`: Texto livre
- `telefone_contato`: Exatamente 8 dígitos
- `celular_contato`: Exatamente 11 dígitos

### Campos opcionais (PUT):
- Todos os campos podem ser atualizados individualmente

## Respostas da API

### Sucesso (200/201):
```json
{
    "success": true,
    "message": "Operação realizada com sucesso",
    "timestamp": "2025-12-17 10:30:00",
    "data": {
        // dados do contato ou lista
    }
}
```

### Erro (400/404/500):
```json
{
    "error": true,
    "message": "Descrição do erro",
    "timestamp": "2025-12-17 10:30:00",
    "errors": {
        // detalhes dos erros de validação (quando aplicável)
    }
}
```

## Como testar

### 1. Via cURL:

**Criar contato:**
```bash
curl -X POST http://localhost/api/contatos \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "Maria Silva",
    "email": "maria@example.com",
    "data_nascimento": "1985-03-20",
    "profissao": "Analista de Sistemas",
    "telefone_contato": "33445566",
    "celular_contato": "11987654321"
  }'
```

**Listar contatos:**
```bash
curl http://localhost/api/contatos
```

**Buscar por ID:**
```bash
curl http://localhost/api/contatos/1
```

**Atualizar:**
```bash
curl -X PUT http://localhost/api/contatos/1 \
  -H "Content-Type: application/json" \
  -d '{"celular_contato": "11888887777"}'
```

**Excluir:**
```bash
curl -X DELETE http://localhost/api/contatos/1
```

### 2. Via Postman/Insomnia:

Importe as rotas acima e configure a base URL para seu ambiente local.

### 3. Via VS Code com extensão REST Client:

Crie um arquivo `.http` e use as requisições HTTP diretamente no editor.