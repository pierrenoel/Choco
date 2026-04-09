# 🍫 ChocoPHP Framework

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892bf.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

**ChocoPHP** is a modern, lightweight PHP MVC framework designed for speed, simplicity, and developer happiness. It features a custom CLI, a powerful routing engine, and seamless Vite integration.

---

## 📂 Project Structure

```text
choco-framework/
├── bin/                # CLI Executable (choco)
├── database/           # Migrations and Seeds
├── public/             # Document Root (index.php, compiled assets)
├── resources/          # Views (PHP), CSS, and JS
├── routes/             # Web and API Route definitions
├── src/                # Core Framework Logic (Controllers, Core, Helpers)
│   ├── Console/        # CLI Commands
│   ├── Core/           # Framework Engine (Router, DB)
│   └── helpers.php     # Global Helper Functions
└── vendor/             # Composer Dependencies

```

## ✨ Features

- Lightweight MVC architecture with controllers, views, and routes
- Simple routing engine supporting `GET`, `POST`, and `DELETE` routes
- Dynamic route parameters like `/user/{id}`
- Base `Controller` class with a custom view renderer and layout support
- Templating directives: `{{ }}`, `@if`, `@else`, `@endif`, `@foreach`, `@endforeach`, `@csrf`
- Base `Repository` class with PDO database access and reusable CRUD helpers
- Environment configuration with `vlucas/phpdotenv`
- CSRF protection for form submissions
- Built-in `choco` CLI commands for development tasks
- Vite integration for frontend assets via `resources/css` and `resources/js`

## 🧩 Core Components

- `src/Core/Router.php` — routes requests and resolves controllers/actions
- `src/Core/Controller.php` — renders views and handles layout injection
- `src/Core/Repository.php` — connects to the database and provides `all()`, `find()`, and `create()` methods
- `src/helpers.php` — global helper functions for app-level utilities

## 🧰 CLI Commands

The project includes custom CLI commands under `src/Console`.

- `php bin/choco list` — show all available commands
- `php bin/choco choco:hello` — verify the CLI and print a welcome message
- `php bin/choco serve` — run the built-in PHP development server
  - optional flag: `--port` or `-p` to change the port (default: `8000`)
- `php bin/choco make:controller` — create a new controller interactively
  - optional flag: `--model` or `-m` to also create a related repository model
