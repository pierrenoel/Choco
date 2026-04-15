<?php 

namespace Choco\Entities;
use Choco\Core\Attributes\Database\AutoIncrement;
use Choco\Core\Attributes\Database\Column;
use Choco\Core\Attributes\Database\Id;
use Choco\Core\Attributes\Database\Table;
use Choco\Core\Attributes\Validation\Min;
use Choco\Core\Attributes\Validation\Required;

#[Table('users')]
class User 
{
    #[Column(type: 'int')]
    #[Id()]
    #[AutoIncrement()]
    protected int $id;

    #[Required(message:"The name is required")]
    #[Min(min:5,message:"The name is min 5 char")]
    #[Column(type: 'varchar', length: 100, nullable: false, unique:true)]
    protected string $name;

    #[Required(message:"The email is required")]
    #[Min(min:5,message:"The name is min 5 char")]
    #[Column(type: 'varchar', length: 100, nullable: false, unique:true)]
    protected string $email;

    public function getName() : string
    {
        return $this->name;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function setEmail(string $email) : void
    {
        $this->email = $email;
    }
}
