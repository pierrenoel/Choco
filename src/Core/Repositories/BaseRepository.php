<?php 

namespace Choco\Core\Repositories;

use Choco\Core\Repository;

class BaseRepository extends Repository
{
    public function __construct(){
        parent::__construct();
    }

    public function generateDatabase($file)
    {
        $file = file_get_contents($file);

        $explode = explode("\n",$file);
        $sql = implode(" ",$explode);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }
}