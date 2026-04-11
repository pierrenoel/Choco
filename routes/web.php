<?php 

use Choco\Controllers\HomeController;
use Choco\Controllers\UserController;

$router->get("/",[HomeController::class,"index"]);
$router->get('/user/create',[UserController::class,"create"]);
$router->post("/user/create",[UserController::class,"store"]);
$router->get("/user/{id}",[UserController::class,"show"]); 
$router->delete("/user/delete/{id}",[UserController::class,"delete"]);
$router->get("/user/edit/{id}",[UserController::class,"edit"]);
$router->put("/user/edit/{id}",[UserController::class,"update"]);