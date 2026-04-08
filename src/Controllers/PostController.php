<?php 

namespace Choco\Controllers;
use Choco\Core\Controller;

class PostController extends Controller
{
    public function create()
    {
        echo $this->view("post/create",["title" => "new Post"]);
    }

    public function save(array $request)
    {
        $csrf = $request["csrf"];
        
        if($_SESSION["token_csrf"] !== $csrf) $this->redirect("/not-found",404);
    }
}