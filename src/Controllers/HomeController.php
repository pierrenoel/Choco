<?php 

namespace Choco\Controllers;
use Choco\Core\Controller;
use Choco\Repositories\UserRepository;

class HomeController extends Controller
{
    public function index()
    {
        $userRepository = new UserRepository();
        $users = $userRepository->all();

        echo $this->view("index",[
            'title' => "Home",
            'flag' => true,
            'users' => $users
        ]);
    }
}