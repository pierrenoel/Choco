# 🍫 ChocoPHP Framework

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892bf.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

**ChocoPHP** is a modern, lightweight PHP MVC framework designed for speed, simplicity, and developer happiness. It features a custom CLI, a powerful routing engine, and seamless Vite integration.

---

## 📂 Project Structure

```text
choco-framework/
|-  migrations/         # SQL Files
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

- `src/Core/Attributes` — contains attributes for entities.
- `src/Services` — contains application services.
- `src/App` — contains the main application files.

- `src/Core/Controller.php` — handles controllers, view rendering, and layout injection.
- `src/Core/Repository.php` — handles data access and provides all(), find(), and create() methods.
- `src/Core/Request.php` — handles HTTP requests (method, URI, parameters, etc.).
- `src/Core/Router.php` — handles routing and resolves controllers/actions.

- `src/helpers.php` — contains global utility helper functions for the application.

## 🧰 CLI Commands

The project includes custom CLI commands under `src/Console`.

### Options

- `-h, --help` — Display help for a given command. If no command is specified, shows the list command help.
- `--silent` — Do not output any messages.
- `-q, --quiet` — Only display errors; suppress all other output.
- `-V, --version` — Display the application version.
- `--ansi | --no-ansi` — Force or disable ANSI output.
- `-n, --no-interaction` — Disable interactive prompts.
- `-v | -vv | -vvv, --verbose` — Increase verbosity (1 = normal, 2 = verbose, 3 = debug).

---

## 📦 Available Commands

- `completion` — Dump the shell completion script.
- `help` — Display help for a command.
- `list` — List all available commands.
- `serve` — Start the ChocoPHP development server.

### `make` commands

- `make:controller` — Create a new controller.
- `make:migration` — Run a migration.

---

## ⚙️ Usage Examples

- `php bin/choco list` — Show all available commands.
- `php bin/choco choco:hello` — Verify the CLI and print a welcome message.
- `php bin/choco serve` — Start the built-in PHP development server.
  - Optional: `--port` or `-p` to change the port (default: `8000`).
- `php bin/choco make:controller` — Create a new controller interactively.
  - Optional: `--model` or `-m` to also create a related repository model.
