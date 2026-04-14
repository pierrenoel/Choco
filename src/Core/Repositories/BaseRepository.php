<?php 

namespace Choco\Core\Repositories;

use Choco\Core\Repository;

class BaseRepository extends Repository
{
    public function __construct(){
        parent::__construct();
    }

    public function generateDatabase(array $file)
    {
        foreach($file as $line){
            $sql = $line;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
    }
}