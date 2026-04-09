<?php

session_start();

require_once '../vendor/autoload.php';

use Dotenv\Dotenv;
use Choco\Core\Router;

// Dotenv
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// If faut ici générer le code csrf
if(!isset($_SESSION["token_csrf"])) $_SESSION["token_csrf"] = bin2hex(random_bytes(5)); 

// Routes
$router = new Router();
require __DIR__ . "../../routes/web.php";
$router->run();
