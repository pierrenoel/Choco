<?php 

namespace Choco\Controllers;
use Choco\Core\Controller;
use Choco\Repositories\UserRepository;
use Choco\Core\Request;

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

        $this->userRepository->create([
            "name" => $request->post("name"),
            "email" => $request->post("email")
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