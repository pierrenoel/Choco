<?php 

// ['CASCADE', 'RESTRICT', 'NO ACTION', 'SET NULL'];

namespace Choco\Entities;

use Choco\Core\Attributes\Database\AutoIncrement;
use Choco\Core\Attributes\Database\Column;
use Choco\Core\Attributes\Database\ForeignKey;
use Choco\Core\Attributes\Database\Id;
use Choco\Core\Attributes\Database\Table;
use Choco\Core\Attributes\Relation\ManyToOne;
use Choco\Core\Attributes\Relation\OneToOne;

#[Table('posts')]
class Post 
{
    #[Column(type: 'int')]
    #[Id()]
    #[AutoIncrement()]
    protected int $id;

    #[Column(type: 'varchar', length: 50, nullable: false)]
    protected string $title;

    #[Column(type:"int")]
    #[ForeignKey(entity:User::class,onDelete:"cascade", onUpdate:"cascade")]
    protected int $user_id;

    #[Column(type:"int")]
    #[ForeignKey(entity:Category::class,onDelete:"cascade", onUpdate:"cascade")]
    protected int $category_id;

    #[ManyToOne(target: User::class)]
    protected User $user;

    #[ManyToOne(target: Category::class)]
    protected Category $category;

    public function getTitle() : string
    {
        return $this->title;
    }
    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }
}
