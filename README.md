# Laravel Task API

A RESTful task management API built with [Laravel 12](https://laravel.com) and [Laravel Sanctum](https://laravel.com/docs/sanctum). Supports user authentication, versioned task resources (v1 public, v2 authenticated & policy-guarded), and task completion toggling.

## Features

- **Authentication** — Register, login, and logout via Sanctum API tokens.
- **Task CRUD** — Create, read, update, and delete tasks.
- **Task completion** — Toggle a task's `is_completed` status via a dedicated endpoint.
- **API versioning** — `v1` (public task access) and `v2` (authenticated, user-scoped, policy-authorized).
- **Form Request validation** — Dedicated request classes for store, update, complete, login, and register.
- **API Resources** — `TaskResource` transforms Eloquent models into consistent JSON responses.
- **Authorization policies** — v2 endpoints use Laravel Gates/Policies for `viewAny`, `create`, `view`, `update`, and `delete`.
- **Testing** — Pest/PHPUnit ready with model factories.

## Tech Stack

- [PHP ^8.2](https://www.php.net/)
- [Laravel Framework ^12.0](https://laravel.com)
- [Laravel Sanctum ^4.0](https://laravel.com/docs/sanctum)
- [PHPUnit ^12.5](https://phpunit.de/)

## Project Structure

```
routes/
  api.php              # Auth routes + version groups
  api/v1.php           # v1 task routes (public)
  api/v2.php           # v2 task routes (auth:sanctum)
app/
  Http/
    Controllers/
      Api/
        V1/TaskController.php
        V1/CompleteTaskController.php
        V2/TaskController.php
        V2/CompleteTaskController.php
      Auth/LoginController.php
      Auth/RegisterController.php
      Auth/LogoutController.php
    Requests/
      StoreTaskRequest.php
      UpdateTaskRequest.php
      CompleteTaskRequest.php
      LoginRequest.php
      RegisterRequest.php
    Resources/
      TaskResource.php
  Models/
    User.php           # name, email, password (hashed), tasks()
    Task.php           # name, is_completed, user_id, user()
database/
  migrations/
    create_tasks_table.php
    add_user_id_to_tasks_table.php
    create_personal_access_tokens_table.php
```

## API Endpoints

### Authentication

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/auth/register` | Register a new user | No |
| POST | `/auth/login` | Authenticate and issue token | No |
| POST | `/auth/logout` | Revoke current token | Yes |
| GET | `/user` | Get authenticated user | Yes |

### Tasks (v1 — public)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/tasks` | List all tasks |
| POST | `/api/v1/tasks` | Create a task |
| GET | `/api/v1/tasks/{task}` | Get a single task |
| PUT/PATCH | `/api/v1/tasks/{task}` | Update a task |
| DELETE | `/api/v1/tasks/{task}` | Delete a task |
| PATCH | `/api/v1/tasks/{task}/complete` | Toggle task completion |

### Tasks (v2 — authenticated + policies)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v2/tasks` | List authenticated user's tasks |
| POST | `/api/v2/tasks` | Create a task (owned by user) |
| GET | `/api/v2/tasks/{task}` | Get a single task (if authorized) |
| PUT/PATCH | `/api/v2/tasks/{task}` | Update a task (if authorized) |
| DELETE | `/api/v2/tasks/{task}` | Delete a task (if authorized) |
| PATCH | `/api/v2/tasks/{task}/complete` | Complete a task (if authorized) |

## Request / Response Examples

### Register

**Request**
```http
POST /auth/register
Content-Type: application/json

{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response** — `201 Created` (authenticated session)

### Login

**Request**
```http
POST /auth/login
Content-Type: application/json

{
  "email": "jane@example.com",
  "password": "password123"
}
```

**Response** — `204 No Content` (session regenerated)

### Create Task (v2)

**Request**
```http
POST /api/v2/tasks
Authorization: Bearer <token>
Content-Type: application/json

{
  "name": "Review pull request"
}
```

**Response** — `201 Created`
```json
{
  "id": 1,
  "name": "Review pull request",
  "is_completed": false
}
```

### Complete Task

**Request**
```http
PATCH /api/v2/tasks/1/complete
Authorization: Bearer <token>
Content-Type: application/json

{
  "is_completed": true
}
```

**Response** — `200 OK`
```json
{
  "id": 1,
  "name": "Review pull request",
  "is_completed": true
}
```

## Installation

1. Clone the repository:

```bash
git clone <repository-url>
cd laravel_api
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install Node dependencies (optional, for Vite assets):

```bash
npm install
```

4. Copy the environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`, then run migrations:

```bash
php artisan migrate
```

6. Serve the application:

```bash
php artisan serve
```

### One-command setup

```bash
composer run setup
```

## Testing

Run the test suite:

```bash
composer run test
```

Or directly with artisan:

```bash
php artisan test
```

## Authentication

This API uses [Laravel Sanctum](https://laravel.com/docs/sanctum) for token-based authentication. Protected routes require the `auth:sanctum` middleware. Obtain a token by logging in, then send it as a `Bearer` token in the `Authorization` header.

## Versioning

API versions are isolated under route prefixes (`/api/v1`, `/api/v2`) so existing clients are not impacted by breaking changes as the API grows.

- **v1** — Public access to all tasks.
- **v2** — Requires Sanctum authentication; tasks are scoped to the authenticated user and protected by Gate policies.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
