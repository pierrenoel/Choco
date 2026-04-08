<?php

session_start();

require_once '../vendor/autoload.php';

use Dotenv\Dotenv;
use Choco\Core\Router;

// Dotenv
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Console

// Routes
$router = new Router();
require __DIR__ . "../../routes/web.php";
$router->run();
