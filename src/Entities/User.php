<?php 

namespace Choco\Entities;
use Choco\Core\Attributes\Column;

class User 
{
    #[Column(type: 'string', length: 50, nullable: false)]
    protected string $name;

    #[Column(type: 'string', length: 100, nullable: true)]
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
