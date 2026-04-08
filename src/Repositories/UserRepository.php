<?php 

namespace Cariboo\Choco\Repositories;

class UserRepository extends Repository
{
    public function __construct(){
        parent::__construct();
    }

    protected $table = "users";
}