# API UserFlow (Laravel 12 + Sail)

Este projeto implementa uma API RESTful para gerenciamento de usuários e endereços, construído com Laravel 12 e PHP 8.3, e projetado para desenvolvimento com Laravel Sail (Docker).

## Funcionalidades

-   **Arquitetura em Camadas:** Separação estrita de Controllers, Services e Repositories para responsabilidades claras.
-   **Princípios S.O.L.I.D.:** Aderência aos princípios S.O.L.I.D., especialmente Responsabilidade Única e Inversão de Dependência.
-   **Desenvolvimento Orientado a Testes (TDD):** Testes de funcionalidade abrangentes usando Pest para todos os endpoints da API, cobrindo cenários de sucesso, erros de validação, não encontrado e não autorizado.
-   **Form Requests:** Validação de entrada robusta usando Form Requests do Laravel.
-   **API Resources:** Respostas JSON padronizadas e prevenção de vazamento de dados sensíveis usando API Resources do Laravel.
-   **Autenticação:** Autenticação de API segura com Laravel Sanctum.
-   **Padrão Repository:** Abstração do acesso ao banco de dados com interfaces para melhor manutenibilidade e testabilidade.
-   **Soft Deletes:** Usuários podem ser excluídos de forma lógica (*soft-deleted*), permitindo recuperação.
-   **Policies:** Controle de autorização refinado para gerenciamento de usuários e endereços.

## Pré-requisitos

Antes de começar, certifique-se de ter o seguinte instalado em seu sistema:

-   [Docker Desktop](https://www.docker.com/products/docker-desktop)

## Instalação e Execução

Siga estes passos para configurar e executar o projeto:

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/sreduard0/teste-target.git
    cd teste-target
    ```

2.  **Instale as dependências do Composer:**
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

3.  **Inicie o Laravel Sail (contêineres Docker):**
    ```bash
    ./vendor/bin/sail up -d
    ```

4.  **Copie o arquivo de ambiente:**
    ```bash
    cp .env.example .env
    ```

5.  **Gere a chave da aplicação:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6.  **Execute as migrações e popule o banco de dados:**
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

    *Nota: O seeder criará um usuário administrador (`admin@example.com` / `password`) e 5 usuários comuns com 2 endereços cada.*

7.  **Acesse a API:**
    A API estará disponível em `http://localhost` (ou na porta configurada em seu arquivo `.env`).

## Executando Testes

Para executar os testes de funcionalidade, execute o seguinte comando:

```bash
./vendor/bin/sail test
