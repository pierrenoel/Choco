<?php 

namespace Cariboo\Choco\Controllers;
use Cariboo\Choco\Repositories\UserRepository;

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
}