<?php 

use Cariboo\Choco\Controllers\HomeController;
use Cariboo\Choco\Controllers\PostController;
use Cariboo\Choco\Controllers\UserController;


$router->get("/",[HomeController::class,"index"]);
$router->get("/user/{id}",[UserController::class,"show"]); 

