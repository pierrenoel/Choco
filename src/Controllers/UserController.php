<?php 

namespace Choco\Controllers;
use Choco\Core\Controller;
use Choco\Repositories\UserRepository;
use Choco\Core\Request;
use Choco\Entities\User;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(){
        $this->userRepository = new UserRepository();
    }

    public function show(Request $request)
    {   
        $id = $request->param(0);
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

    public function store(Request $request)
    {
        $this->csrf();

        $user = new User();
        
        $user->setName($request->post("name"));
        $user->setMail($request->post("email"));

        $this->userRepository->create([
            "name" => $user->getName(),
            "email" => $user->getMail()
        ]);

        redirect("/");
    }

    public function edit(Request $request)
    {
        $id = $request->param(0);
        $user = $this->userRepository->find($id);

        echo $this->view("user/edit",[
            "title" => "Edit user",
            "user" => $user
        ]);
    }

    public function update(Request $request)
    {
        $this->csrf();

        $id = $request->param(0);
        $name = $request->post("name");
        $email = $request->post("email");

        $this->userRepository->update([
            "name" => $name,
            "email" => $email
        ],$id);

        redirect("/");
    }

    public function delete(Request $request)
    {
        $id = $request->param(0);
        $this->userRepository->delete($id);

        redirect("/");
    }
}