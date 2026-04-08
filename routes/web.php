<?php 

use Choco\Controllers\HomeController;
use Choco\Controllers\PostController;
use Choco\Controllers\UserController;


$router->get("/",[HomeController::class,"index"]);
$router->get('/user/create',[UserController::class,"create"]);
$router->post("/user/create",[UserController::class,"store"]);
$router->get("/user/{id}",[UserController::class,"show"]); 
