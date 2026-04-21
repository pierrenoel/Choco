<?php 

namespace Choco\Repositories;
use Choco\Core\Repository;

class CategoryRepository extends Repository
{
    public function __construct(){
        parent::__construct();
    }

    protected $table = "categories";
    protected $entity = "Category";

}