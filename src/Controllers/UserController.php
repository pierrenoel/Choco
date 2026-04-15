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
        $user = new User();

        $user->setName($request->post("name"));
        $user->setEmail($request->post("email"));

        $errors = $this->validate($user);
        
        if (!empty($errors)) {

            $_SESSION["errors"] = $errors;
            $_SESSION["old"] = $_POST;

            echo $this->view("user/create", [
                "title" => "Nouvel utilisateur",
                "errors" => $errors
            ]);

            return;
        }

        $this->userRepository->create([
            "name" => $user->getName(),
            "email" => $user->getEmail()
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

        $user = new User();

        $user->setName($request->post("name"));
        $user->setMail($request->post("email"));

        $this->userRepository->update([
            "name" => $user->getName(),
            "email" => $user->getMail()
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