<?php 

namespace Choco\Repositories;
use Choco\Core\Repository;

class UserRepository extends Repository
{
    public function __construct(){
        parent::__construct();
    }

    protected $table = "users";
}