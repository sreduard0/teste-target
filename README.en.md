uardo# Technical Challenge: REST API for User and Address Management

This project implements a RESTful API for managing users and their respective addresses, with JWT authentication. It was developed using Laravel 12 and PHP 8+, with support for Laravel Sail for the development environment.

## 🚀 Getting Started

Follow the instructions below to set up and run the project in your local environment.

### Prerequisites

Make sure you have the following software installed on your machine:

*   **Docker Desktop:** Required to run Laravel Sail.
*   **PHP (optional, but recommended for Composer):** Version 8.2 or higher.
*   **Composer:** PHP dependency manager.
*   **Node.js and npm/Yarn (optional, for frontend if applicable):** To compile assets, although it's not the main focus of this API.

### Installation and Configuration

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/sreduard0/teste-target.git
    cd teste-target # Or your project folder name
    ```

2.  **Install Composer dependencies:**
    ```bash
    composer install
    ```

3.  **Configure the environment:**
    Create the `.env` file from `.env.example`:
    ```bash
    cp .env.example .env
    ```

4.  **Generate the application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Start Laravel Sail:**
    ```bash
    ./vendor/bin/sail up -d
    ```
    This will build and start the necessary Docker containers (PHP, Nginx, MySQL/MariaDB, Redis, etc.). It may take a few minutes the first time.

6.  **Run database migrations:**
    ```bash
    ./vendor/bin/sail artisan migrate
    ```

7.  **Run seeders (optional, for test data):**
    ```bash
    ./vendor/bin/sail artisan db:seed
    ```

8.  **The application will be available at:** `http://localhost`

## 🧪 Running Tests

To ensure the quality and correct functioning of the API, run the automated tests with PHPUnit.

```bash
./vendor/bin/sail artisan test
```

## 💡 API Usage Examples

You can use tools like Postman, Insomnia, or `curl` to interact with the API.

### 1. User Registration (Public)

*   **Endpoint:** `POST /api/users`
*   **Request Body (JSON):**
    ```json
    {
        "name": "New User",
        "email": "new@example.com",
        "password": "password",
        "password_confirmation": "password",
        "cpf": "123.456.789-01",
        "phone": "11987654322"
    }
    ```
*   **Success Response (201 Created):**
    ```json
    {
        "data": {
            "id": 1,
            "name": "New User",
            "email": "new@example.com",
            "cpf": "123.456.789-01",
            "phone": "11987654322",
            "created_at": "2023-10-27T10:00:00.000000Z",
            "updated_at": "2023-10-27T10:00:00.000000Z"
        }
    }
    ```

### 2. User Login

*   **Endpoint:** `POST /api/login`
*   **Request Body (JSON):**
    ```json
    {
        "email": "new@example.com",
        "password": "password"
    }
    ```
*   **Success Response (200 OK):**
    ```json
    {
        "token": "YOUR_JWT_TOKEN_HERE"
    }
    ```
    **Save this token!** It will be used to authenticate subsequent requests.

### 3. Get Authenticated User Profile

*   **Endpoint:** `GET /api/me`
*   **Headers:**
    *   `Authorization: Bearer YOUR_JWT_TOKEN_HERE`
*   **Success Response (200 OK):** Returns the authenticated user's data.

### 4. List User Addresses

*   **Endpoint:** `GET /api/users/{user_id}/addresses`
*   **Headers:**
    *   `Authorization: Bearer YOUR_JWT_TOKEN_HERE`
*   **Success Response (200 OK):** Returns a list of addresses.

### 5. Create Address for a User

*   **Endpoint:** `POST /api/users/{user_id}/addresses`
*   **Headers:**
    *   `Authorization: Bearer YOUR_JWT_TOKEN_HERE`
*   **Request Body (JSON):**
    ```json
    {
        "user_id": 1, // ID of the user to whom the address belongs
        "street": "Example Street",
        "number": "123",
        "neighborhood": "Downtown",
        "complement": "Apt 10",
        "zip_code": "01000-000"
    }
    ```
*   **Success Response (201 Created):** Returns the created address data.

## 🛑 Stopping Laravel Sail

To stop the Docker containers:

```bash
./vendor/bin/sail down
```

## 🤝 Developer
Eduardo Martins
