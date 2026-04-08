<?php 

namespace Choco\Core;

abstract class Repository 
{
    protected \PDO $pdo;

    public function __construct(){
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $dsn = "mysql:host={$_ENV["APP_HOST"]};dbname={$_ENV["APP_DATABASE"]};charset=utf8";
            $username = $_ENV["APP_USERNAME"];
            $password = $_ENV["APP_PASSWORD"];

            $this->pdo = new \PDO($dsn, $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            die("Erreur PDO : " . $e->getMessage());
        }
    }

    private function getTableName() : string 
    {
        $rc = new \ReflectionClass($this);

        if (!$rc->hasProperty("table")) {
            throw new \Exception("La propriété 'table' est manquante.");
        }

        $property = $rc->getProperty("table");
        $property->setAccessible(true);

        return $property->getValue($this);
    }

    public function all(): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()}");
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            echo "Error {$e->getMessage()}";
            return [];
        }
    }

    public function find(int $id) : mixed
    {
        try{
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
            $stmt->execute(["id" => $id]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result ?: null;
    
        }catch(\PDOException $e){
            echo "Error {$e->getMessage()}";
            return [];
        }
    }

    public function create(array $array) 
    {
        if(empty($array)) throw new \InvalidArgumentException("Data cannot be empty");
        
        unset($array["csrf"]);
        $keys = \array_keys($array);
        $explodedKeys = implode(",",$keys);
        $explodeKeysWithDoubleDots = implode(",",array_map(fn($item) => ":{$item}",$keys));

        $sql = "INSERT INTO {$this->getTableName()} ({$explodedKeys}) VALUES ({$explodeKeysWithDoubleDots})";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($array);

        return $this->pdo->lastInsertId();
    }
}