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

    public function store(array $post)
    {
        $this->csrf();

        // if($_SERVER["REQUEST_METHOD"] != "POST") redirect("/not-found",402);

        $this->userRepository->create($post);

        redirect("/");
    }

    public function delete(int $id)
    {

    }
}