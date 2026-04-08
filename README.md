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

