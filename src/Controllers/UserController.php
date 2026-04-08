<?php 

namespace Choco\Controllers;
use Choco\Core\Controller;
use Choco\Repositories\UserRepository;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(){
        $this->userRepository = new UserRepository();
    }

    public function show(int $id)
    {
       $user = $this->userRepository->find($id);

        echo $this->view("user/show",
        [
            "title" => "Profile de {$user["name"]}",
            "user" => $user,            
        ]);
    }

    public function create() 
    {
        echo $this->view("user/create",[
            "title" => "Nouvel utilisateur"
        ]);
    }

    public function store(array $request)
    {
        $csrf = $request["csrf"];
        if($_SESSION["token_csrf"] !== $csrf) $this->redirect("/not-found",404);

        $this->userRepository->create($request);

        $this->redirect("/");
    }
}