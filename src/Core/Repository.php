<?php 

namespace Choco\Core;

use Choco\Core\Services\Entity\EntityService;

abstract class Repository 
{
    protected \PDO $pdo;
    protected EntityService $entityService;

    public function __construct(){
        $this->connect();
        $this->entityService = new EntityService();
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
            throw new \Exception("Erreur PDO {$e->getMessage()}");
        }
    }

    private function getAttribute(string $table) : string 
    {
        $rc = new \ReflectionClass($this);

        if (!$rc->hasProperty($table)) {
            throw new \Exception("La propriété {$table} est manquante.");
        }

        $property = $rc->getProperty($table);
        $property->setAccessible(true);

        return $property->getValue($this);
    }

    public function all(): array
    {   
        return $this->entityService->getRelatedTables($this->getAttribute("table")) 
        ? $this->innerJoin() 
        : $this->allMethod();
    }

    public function find(int $id) : mixed
    {
        try{
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->getAttribute("table")} WHERE id = :id");
            $stmt->execute(["id" => $id]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result ?: null; 
    
        }catch(\PDOException $e){
            echo "Error {$e->getMessage()}";
            return [];
        }
    }

    public function create(array $array) : mixed
    {        
        $keys = \array_keys($array);
        $explodedKeys = implode(",",$keys);
        $explodeKeysWithDoubleDots = implode(",",array_map(fn($item) => ":{$item}",$keys));

        try{
            $sql = "INSERT INTO {$this->getAttribute("table")} ({$explodedKeys}) VALUES ({$explodeKeysWithDoubleDots})";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute($array);

            return $this->pdo->lastInsertId();
        }catch(\PDOException $e){
            echo "Error {$e->getMessage()}";
            return false;
        }
    }

    public function update(array $array, int $id) 
    {
        $keys = \array_keys($array);
        $KeysUpdated = implode(",",array_map(fn($item) => "{$item}=?",$keys));
        $values = implode(",",\array_values($array));

        try{
            $sql = "UPDATE {$this->getAttribute("table")} SET {$KeysUpdated} WHERE id = {$id}";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(explode(",",$values));
        }catch(\PDOException $e){
            echo "Error {$e->getMessage()}";
            return false;
        }
    }

    public function delete(int $id) : bool
    {
        $result = $this->find($id);
        if(!$result) throw new \Exception("Record with id {$id} not found");

        try{
            $stmt = $this->pdo->prepare("DELETE FROM {$this->getAttribute("table")} WHERE id = :id");
            $stmt->execute(["id" => $id]);
            return true;
        }
        catch(\PDOException $e){
            echo "Error {$e->getMessage()}";
            return false;
        }
    }

    private function allMethod()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM {$this->getAttribute("table")}");
            
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            echo "Error {$e->getMessage()}";
            return [];
        }
    }

    private function innerJoin()
    {
        $entity = $this->getAttribute("entity"); // Post
        $table = $this->getAttribute("table"); // Posts

        // Called Entity
        $entityAlias = \strtolower($entity);
        $currentEntity = $this->entityService->getQualifiedColumns($entity, $entityAlias);

        // Related Table(s)
        $relationProjections = [];
        $related = $this->entityService->getRelatedTables($table);
  
        foreach($related as $key => $value){
            $alias = $this->entityService->getTableNameFromClass($value);
            $relationProjections[$this->entityService->getTableNameFromClass($value)]["alias"] = $key;
            $relationProjections[$this->entityService->getTableNameFromClass($value)]["select"] = $this->entityService->getQualifiedColumns($key,$alias);
        }
    
        $columns = trim(array_reduce($relationProjections,fn($acc,$item) => $acc .= $item["select"]. ", "),"");
        $columns = \mb_substr($columns,0,-2);

        $inners = "";
        foreach($relationProjections as $key => $value){
            $inners .= "INNER JOIN {$key} ON {$entityAlias}.{$value["alias"]}_id = {$key}.id ";
        }

        $sql = "SELECT {$currentEntity}, {$columns} FROM {$table} {$entityAlias} $inners";
          
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $this->entityService->hydrate($rows); 

        } catch (\PDOException $e) {
            echo "Error {$e->getMessage()}";
            return false;
        }
    }
}