# UserFlow API (Laravel 12 + Sail)

This project implements a RESTful API for user and address management, built with Laravel 12 and PHP 8.3, designed for development with Laravel Sail (Docker).

## Features

-   **Layered Architecture:** Strict separation of Controllers, Services, and Repositories for clear responsibilities.
-   **S.O.L.I.D. Principles:** Adherence to S.O.L.I.D. principles, especially Single Responsibility and Dependency Inversion.
-   **Test-Driven Development (TDD):** Comprehensive feature tests using Pest for all API endpoints, covering success, validation errors, not found, and unauthorized scenarios.
-   **Form Requests:** Robust input validation using Laravel Form Requests.
-   **API Resources:** Standardized JSON responses and prevention of sensitive data leakage using Laravel API Resources.
-   **Authentication:** Secure API authentication powered by Laravel Sanctum.
-   **Repository Pattern:** Abstraction of database access with interfaces for better maintainability and testability.
-   **Soft Deletes:** Users can be soft-deleted, allowing for recovery.
-   **Policies:** Fine-grained authorization control for user and address management.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

-   [Docker Desktop](https://www.docker.com/products/docker-desktop)

## Installation and Execution

Follow these steps to set up and run the project:

1.  **Clone the repository:**
    ```bash
    git clone <your-repository-url>
    cd teste-target
    ```

2.  **Install Composer dependencies:**
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

3.  **Start Laravel Sail (Docker containers):**
    ```bash
    ./vendor/bin/sail up -d
    ```

4.  **Copy the environment file:**
    ```bash
    cp .env.example .env
    ```

5.  **Generate the application key:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6.  **Run database migrations and seed the database:**
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

    _Note: The seeder will create an admin user (`admin@example.com` / `password`) and 5 regular users with 2 addresses each._

7.  **Access the API:**
    The API will be available at `http://localhost` (or the port configured in your `.env` file).

## Running Tests

To run the feature tests, execute the following command:

```bash
./vendor/bin/sail test
```

## API Documentation

### Authentication

| Endpoint | HTTP Verb | Description | Example Payload | Example Response |
| :------- | :-------- | :---------- | :-------------- | :--------------- |
| `/api/login` | `POST` | Authenticate a user and return an API token. | `{"email": "user@example.com", "password": "password"}` | `{"message": "Login successful", "token": "...", "user": {...}}` |
| `/api/logout` | `POST` | Revoke the current user's API token. | (None) | `{"message": "Logout successful"}` |
| `/api/me` | `GET` | Get the authenticated user's profile. | (None) | `{"data": {...}}` |

### Users

| Endpoint | HTTP Verb | Description | Example Payload | Example Response |
| :------- | :-------- | :---------- | :-------------- | :--------------- |
| `/api/users` | `POST` | Register a new user. (Public route) | `{"name": "John Doe", "email": "john@example.com", "password": "password", "password_confirmation": "password", "cpf": "12345678901", "phone": "11987654321"}` | `{"data": {...}}` (Status 201) |
| `/api/users` | `GET` | Get a list of all users. (Admin only) | (None) | `{"data": [{...}, {...}]}` |
| `/api/users/{id}` | `GET` | Get a specific user's profile. (Self or Admin) | (None) | `{"data": {...}}` |
| `/api/users/{id}` | `PUT` | Update a user's profile. (Self or Admin) | `{"name": "Updated Name", "phone": "9988776655"}` | `{"data": {...}}` |
| `/api/users/{id}` | `DELETE` | Soft delete a user. (Self or Admin) | (None) | (Status 204, No Content) |

### Addresses

| Endpoint | HTTP Verb | Description | Example Payload | Example Response |
| :------- | :-------- | :---------- | :-------------- | :--------------- |
| `/api/users/{user_id}/addresses` | `GET` | Get all addresses for a specific user. (Self or Admin) | (None) | `{"data": [{...}, {...}]}` |
| `/api/users/{user_id}/addresses` | `POST` | Create a new address for a user. (Self or Admin) | `{"street": "Main St", "number": "123", "neighborhood": "Downtown", "complement": "Apt 1", "zip_code": "12345-678"}` | `{"data": {...}}` (Status 201) |
| `/api/users/{user_id}/addresses/{id}` | `GET` | Get a specific address for a user. (Self or Admin) | (None) | `{"data": {...}}` |
| `/api/users/{user_id}/addresses/{id}` | `PUT` | Update a specific address for a user. (Self or Admin) | `{"street": "New Street", "number": "456"}` | `{"data": {...}}` |
| `/api/users/{user_id}/addresses/{id}` | `DELETE` | Delete a specific address for a user. (Self or Admin) | (None) | (Status 204, No Content) |