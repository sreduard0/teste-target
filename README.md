# Desafio Técnico: API REST para Gerenciamento de Usuários e Endereços

Este projeto implementa uma API RESTful para gerenciamento de usuários e seus respectivos endereços, com autenticação JWT. Foi desenvolvido utilizando Laravel 12 e PHP 8+, com suporte a Laravel Sail para ambiente de desenvolvimento.

## 🚀 Começando

Siga as instruções abaixo para configurar e executar o projeto em seu ambiente local.

### Pré-requisitos

Certifique-se de ter os seguintes softwares instalados em sua máquina:

*   **Docker Desktop:** Necessário para rodar o Laravel Sail.
*   **PHP (opcional, mas recomendado para Composer):** Versão 8.2 ou superior.
*   **Composer:** Gerenciador de dependências do PHP.
*   **Node.js e npm/Yarn (opcional, para frontend se houver):** Para compilar assets, embora não seja o foco principal desta API.

### Instalação e Configuração

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/sreduard0/teste-target.git
    cd teste-target # Ou o nome da pasta do seu projeto
    ```

2.  **Instale as dependências do Composer:**
    ```bash
    composer install
    ```

3.  **Configure o ambiente:**
    Crie o arquivo `.env` a partir do `.env.example`:
    ```bash
    cp .env.example .env
    ```

4.  **Gere a chave da aplicação:**
    ```bash
    php artisan key:generate
    ```

5.  **Inicie o Laravel Sail:**
    ```bash
    ./vendor/bin/sail up -d
    ```
    Isso irá construir e iniciar os containers Docker necessários (PHP, Nginx, MySQL/MariaDB, Redis, etc.). Pode levar alguns minutos na primeira vez.

6.  **Execute as migrações do banco de dados:**
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

7.  **Execute os seeders (opcional, para dados de teste):**
    ```bash
    ./vendor/bin/sail artisan db:seed
    ```

8.  **A aplicação estará disponível em:** `http://localhost`

## 🧪 Executando os Testes

Para garantir a qualidade e o correto funcionamento da API, execute os testes automatizados com PHPUnit.

```bash
./vendor/bin/sail artisan test
```

## 💡 Exemplos de Uso da API

Você pode usar ferramentas como Postman, Insomnia ou `curl` para interagir com a API.

### 1. Registro de Usuário (Público)

*   **Endpoint:** `POST /api/users`
*   **Corpo da Requisição (JSON):**
    ```json
    {
        "name": "Novo Usuário",
        "email": "novo@example.com",
        "password": "password",
        "password_confirmation": "password",
        "cpf": "123.456.789-01",
        "phone": "11987654322"
    }
    ```
*   **Resposta de Sucesso (201 Created):**
    ```json
    {
        "data": {
            "id": 1,
            "name": "Novo Usuário",
            "email": "novo@example.com",
            "cpf": "123.456.789-01",
            "phone": "11987654322",
            "created_at": "2023-10-27T10:00:00.000000Z",
            "updated_at": "2023-10-27T10:00:00.000000Z"
        }
    }
    ```

### 2. Login de Usuário

*   **Endpoint:** `POST /api/login`
*   **Corpo da Requisição (JSON):**
    ```json
    {
        "email": "novo@example.com",
        "password": "password"
    }
    ```
*   **Resposta de Sucesso (200 OK):**
    ```json
    {
        "token": "SEU_TOKEN_JWT_AQUI"
    }
    ```
    **Guarde este token!** Ele será usado para autenticar as requisições subsequentes.

### 3. Obter Perfil do Usuário Autenticado

*   **Endpoint:** `GET /api/me`
*   **Headers:**
    *   `Authorization: Bearer SEU_TOKEN_JWT_AQUI`
*   **Resposta de Sucesso (200 OK):** Retorna os dados do usuário autenticado.

### 4. Listar Endereços de um Usuário

*   **Endpoint:** `GET /api/users/{user_id}/addresses`
*   **Headers:**
    *   `Authorization: Bearer SEU_TOKEN_JWT_AQUI`
*   **Resposta de Sucesso (200 OK):** Retorna uma lista de endereços.

### 5. Criar Endereço para um Usuário

*   **Endpoint:** `POST /api/users/{user_id}/addresses`
*   **Headers:**
    *   `Authorization: Bearer SEU_TOKEN_JWT_AQUI`
*   **Corpo da Requisição (JSON):**
    ```json
    {
        "user_id": 1, // ID do usuário ao qual o endereço pertence
        "street": "Rua Exemplo",
        "number": "123",
        "neighborhood": "Centro",
        "complement": "Apto 10",
        "zip_code": "01000-000"
    }
    ```
*   **Resposta de Sucesso (201 Created):** Retorna os dados do endereço criado.

## 🛑 Parando o Laravel Sail

Para parar os containers Docker:

```bash
./vendor/bin/sail down
```

## 🤝 Developer

Eduardo Martins
