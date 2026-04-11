<?php

namespace Choco\Core;

use Dotenv\Dotenv;
use Throwable;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class App
{
    public function run()
    {
        $this->boot();

        try {
            $this->handleRequest();
        } catch (Throwable $e) {
            if (!$this->isDebug()) {
                $this->handleException($e);
            } else {
                throw $e; 
            }
        }
    }

    private function boot()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        if ($this->isDebug()) {
            $whoops = new Run;
            $whoops->pushHandler(new PrettyPageHandler);
            $whoops->register();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["token_csrf"])) {
            $_SESSION["token_csrf"] = bin2hex(random_bytes(32));
        }
    }

    private function handleRequest()
    {
        $router = new Router();
        require __DIR__ . '/../../routes/web.php';
        $router->run();
    }

    private function handleException(Throwable $e)
    {
        http_response_code(500);

        $this->renderError(
            500,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }

    private function renderError($status, $message, $file, $line, $trace = null)
    {
        // En production, on cherche la vue d'erreur personnalisée
        $path = __DIR__ . "/../../resources/views/errors/{$status}.choco.html";

        if (file_exists($path)) {
            require $path;
            return;
        }

        echo "<h1>Erreur {$status}</h1>";
        echo "<p>Une erreur interne est survenue.</p>";
    }

    private function isDebug(): bool
    {
        return isset($_ENV['APP_DEBUG']) && filter_var($_ENV['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN);
    }
}