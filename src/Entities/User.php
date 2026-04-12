<?php 

namespace Choco\Entities;
use Choco\Core\Attributes\Column;
use Choco\Core\Attributes\Id;
use Choco\Core\Attributes\AutoIncrement;

class User 
{
    #[Column(type: 'int')]
    #[Id()]
    #[AutoIncrement()]
    protected int $id;

    #[Column(type: 'string', length: 50, nullable: false)]
    protected string $name;

    #[Column(type: 'string', length: 100, nullable: false, unique:true)]
    protected string $mail;

    public function getName() : string
    {
        return $this->name;
    }

    public function getMail() : string
    {
        return $this->mail;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function setMail(string $mail) : void
    {
        $this->mail = $mail;
    }
}
