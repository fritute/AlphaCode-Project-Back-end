# AlphaCode Backend

API REST para gerenciamento de contatos desenvolvida em PHP com arquitetura MVC.

## 🚀 Como Baixar e Instalar

### Pré-requisitos
- PHP 7.4 ou superior
- MySQL/MariaDB
- Composer


### 1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/alphacode-backend.git
cd alphacode-backend
```

### 2. Instale as dependências
```bash
composer install
```

### 3. Configure o ambiente
```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas configurações:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=alphacode_db
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
APP_ENV=development
```

### 4. Configure o banco de dados
Execute o script SQL em `config/database.sql` para criar a tabela:
```sql
CREATE TABLE tbl_contatos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome varchar(100) NOT NULL,
    email varchar(300) NOT NULL,
    data_nascimento date NOT NULL,
    profissao varchar(100) NOT NULL,
    telefone_contato varchar(10) NOT NULL,
    celular_contato varchar(11) NOT NULL
);
```

### 5. Configure permissões (Linux/Mac)
```bash
chmod -R 775 storage/
```

### 6. Inicie o servidor
```bash
php -S localhost:8000 -t public
```

A API estará disponível em: `http://localhost:8000`

## 📚 Documentação da API

### Base URL
```
http://localhost:8000
```

### Endpoints Disponíveis

#### 🏠 Informações Gerais
| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/` ou `/api` | Informações básicas da API |
| `GET` | `/api/health` | Status de saúde da aplicação |

#### 👥 Gestão de Contatos

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/contatos` | Listar todos os contatos |
| `POST` | `/api/contatos` | Criar novo contato |
| `GET` | `/api/contatos/{id}` | Buscar contato por ID |
| `PUT` | `/api/contatos/{id}` | Atualizar contato |
| `DELETE` | `/api/contatos/{id}` | Excluir contato |
| `GET` | `/api/contatos/profissao/{profissao}` | Buscar por profissão |
| `GET` | `/api/contatos/ano/{ano}` | Buscar por ano de nascimento |

### Exemplos de Uso

#### 1. Listar todos os contatos
```http
GET /api/contatos
```

**Parâmetros opcionais:**
- `page`: Número da página (padrão: 1)
- `limit`: Limite por página (padrão: 10)
- `search`: Buscar por nome

**Exemplo com filtros:**
```http
GET /api/contatos?search=João&limit=5&page=1
```

#### 2. Criar novo contato
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

#### 3. Buscar contato por ID
```http
GET /api/contatos/1
```

#### 4. Atualizar contato
```http
PUT /api/contatos/1
Content-Type: application/json

{
    "nome": "João Silva Santos",
    "celular_contato": "11888887777"
}
```

#### 5. Excluir contato
```http
DELETE /api/contatos/1
```

#### 6. Buscar por profissão
```http
GET /api/contatos/profissao/Desenvolvedor
```

#### 7. Buscar por ano de nascimento
```http
GET /api/contatos/ano/1990
```

### Validações

#### Campos obrigatórios (POST):
- `nome`: Mínimo 2 caracteres
- `email`: Formato válido e único
- `data_nascimento`: Formato YYYY-MM-DD
- `profissao`: Texto livre
- `telefone_contato`: Exatamente 10 dígitos
- `celular_contato`: Exatamente 11 dígitos

#### Campos opcionais (PUT):
Todos os campos podem ser atualizados individualmente.

### Respostas da API

#### ✅ Sucesso (200/201):
```json
{
    "success": true,
    "message": "Operação realizada com sucesso",
    "timestamp": "2025-12-17 10:30:00",
    "data": {
        "id": 1,
        "nome": "João da Silva",
        "email": "joao@example.com",
        "data_nascimento": "1990-05-15",
        "profissao": "Desenvolvedor",
        "telefone_contato": "3333334444",
        "celular_contato": "11999998888"
    }
}
```

#### ❌ Erro (400/404/500):
```json
{
    "error": true,
    "message": "Descrição do erro",
    "timestamp": "2025-12-17 10:30:00",
    "errors": {
        "email": "Email já está em uso",
        "telefone_contato": "Telefone deve ter 10 dígitos"
    }
}
```

### Testando a API

#### Via cURL:

**Criar contato:**
```bash
curl -X POST http://localhost:8000/api/contatos \
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
curl http://localhost:8000/api/contatos
```

**Buscar por ID:**
```bash
curl http://localhost:8000/api/contatos/1
```

#### Via Postman/Insomnia:
Importe as rotas acima e configure a base URL para `http://localhost:8000`

#### Via VS Code REST Client:
Crie um arquivo `.http` no seu projeto e use as requisições HTTP diretamente no editor.

## 🏗️ Estrutura do Projeto

```
back-end/
├── app/                    # Código da aplicação
│   ├── Controllers/        # Controladores (ContatoController)
│   ├── Models/            # Modelos (ContatoModel)
│   ├── Services/          # Serviços de negócio
│   ├── Middlewares/       # Middlewares personalizados
│   ├── Helpers/           # Funções auxiliares
│   ├── Exceptions/        # Exceções customizadas
│   ├── Routes/            # Definição de rotas (web.php)
│   └── Validators/        # Validadores de dados
├── config/                # Configurações
│   ├── app.php           # Config gerais
│   ├── database.php      # Config do banco
│   ├── database.sql      # Script SQL
│   └── logging.php       # Config de logs
├── public/                # Ponto de entrada
│   └── index.php         # Bootstrap da aplicação
├── storage/               # Armazenamento
│   ├── logs/             # Logs da aplicação
│   ├── cache/            # Cache
│   ├── sessions/         # Sessões
│   └── temp/             # Arquivos temporários
└── composer.json          # Dependências PHP
```

## 🛠️ Tecnologias

- **PHP 7.4+** - Linguagem principal
- **MySQL/MariaDB** - Banco de dados
- **Composer** - Gerenciador de dependências
- **Arquitetura MVC** - Padrão de desenvolvimento

## 🔒 Segurança

- ✅ Proteção contra SQL Injection (prepared statements)
- ✅ Validação rigorosa de entrada de dados
- ✅ Headers de segurança configurados
- ✅ CORS configurado para desenvolvimento
- ✅ Sanitização de dados de entrada

## Configuração do Banco de Dados

1. Crie um banco de dados MySQL
2. Configure as credenciais no arquivo `.env`:
   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_NAME=alpha_code_contatos
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

## Estrutura de Desenvolvimento

### Controllers
Controladores ficam em `app/Controllers/` e devem estender `BaseController`.

### Models
Modelos ficam em `app/Models/` e devem estender `BaseModel` para operações de banco de dados.

### Services
Lógica de negócio complexa deve ficar em `app/Services/`.

### Middlewares
Middlewares para autenticação, logging, etc. ficam em `app/Middlewares/`.

## Testes

Execute os testes com:

```bash
composer run test
```

Para testes com cobertura:

```bash
composer run test:coverage
```

## Segurança

- CORS configurado para desenvolvimento
- Headers de segurança incluídos
- Validação de entrada de dados
- Proteção contra SQL Injection através de prepared statements

## 📊 Logs

Os logs são salvos em `storage/logs/`:
- `app.log` - Logs gerais da aplicação
- `error.log` - Logs de erro
- `access.log` - Logs de acesso

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

