<?php 

namespace Choco\Repositories;
use Choco\Core\Repository;

class PostRepository extends Repository
{
    public function __construct(){
        parent::__construct();
    }

    protected $table = "posts";
    protected $entity = "Post";

}