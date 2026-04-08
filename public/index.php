<?php

session_start();

require_once '../vendor/autoload.php';

use Cariboo\Choco\Core\Router;

$router = new Router();

require __DIR__ . "../../routes/web.php";

$router->run();
