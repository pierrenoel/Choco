<?php 

namespace Choco\Entities;
use Choco\Core\Attributes\Column;
use Choco\Core\Attributes\Id;
use Choco\Core\Attributes\AutoIncrement;

class Post 
{
    #[Column(type: 'int')]
    #[Id()]
    #[AutoIncrement()]
    protected int $id;

   #[Column(type: 'string', length: 50, nullable: false)]
    protected string $title;

    public function getTitle() : string
    {
        return $this->title;
    }
    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }
}
