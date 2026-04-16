<?php 

namespace Choco\Core\Services;

use Choco\Core\Attributes\Database\AutoIncrement;
use Choco\Core\Attributes\Database\Column;
use Choco\Core\Attributes\Database\ForeignKey;
use Choco\Core\Attributes\Database\Id;
use Choco\Core\Attributes\Database\Table;

class MigrationService
{
    protected array $result;
    protected string $tableName;

    public function __construct(
        public $entity
    ){}

    public function getAttributes()
    {
        $reflection = new \ReflectionClass($this->entity);

        // Get the table attribute
        $tableAttributes = $reflection->getAttributes(Table::class);
        $this->tableName = strtolower($reflection->getShortName());

        if (!empty($tableAttributes)) {
            $table = $tableAttributes[0]->newInstance();
            $this->tableName = $table->name;
        }

        $properties = $reflection->getProperties();

        foreach($properties as $property){

            $name = $property->getName();

            if (!isset($this->result[$name])) {
                $this->result[$name] = [];
            }

            // Column
            $attributes = $property->getAttributes(Column::class);
            
            foreach($attributes as $attribute){
                $field = $attribute->newInstance();

                $this->result[$property->getName()] = [
                    "type" => $field->type,
                    "length" => $field->length,
                    "nullable" => $field->nullable,
                    "default" => $field->default,
                    "unique" => $field->unique,
                ];
            }

            // Unique
            $id = $property->getAttributes(Id::class);
            foreach ($id as $item) {
                $this->result[$name]["primaryKey"] = true;
            }

            // Autoincrement
            $autoIncrement = $property->getAttributes(AutoIncrement::class);
            foreach ($autoIncrement as $item) {
                $this->result[$name]["autoIncrement"] = true;
            }

            // ForeignKey
            $foreignKey = $property->getAttributes(ForeignKey::class);
            foreach($foreignKey as $item){
                
                $key = $foreignKey[0]->newInstance();
                
                $entity = new \ReflectionClass($key->entity);
                $entityName = \strtolower($entity->getShortName());
                $table = $entity->getAttributes(Table::class);

                $table = $table[0]->newInstance()->name ?? null;
                
                $this->result[$name]["constraint"] = "fk_{$entityName}";
                $this->result[$name]["fk"] = $property->getName();
                $this->result[$name]["references"] = $table;
                $this->result[$name]["onDelete"] = \strtoupper($key->onDelete);
                $this->result[$name]["onUpdate"] = \strtoupper($key->onUpdate);
            }
        }
         return $this->result;
    }
    

    public function createTable()
    {
        $attributes = $this->getAttributes();

        $sql = "CREATE TABLE IF NOT EXISTS " . $this->tableName . " (";

        foreach($attributes as $name => $attribute){
            $sql .= $name . " " . strtoupper($attribute["type"]);

            if (isset($attribute["length"])) $sql .= "(" . $attribute["length"] . ")";
            
            if (isset($attribute["nullable"]) && !$attribute["nullable"]) $sql .= " NOT NULL";
            else $sql .= " NULL";

            if (isset($attribute["default"])) $sql .= " DEFAULT '" . $attribute["default"] . "'";
        
            if (isset($attribute["unique"]) && $attribute["unique"]) $sql .= " UNIQUE";
            
            if (isset($attribute["primaryKey"]) && $attribute["primaryKey"]) $sql .= " PRIMARY KEY";
            
            if (isset($attribute["autoIncrement"]) && $attribute["autoIncrement"]) $sql .= " AUTO_INCREMENT";
        
            $sql .= ", ";
        }
        $sql = rtrim($sql, ", ") . ") ENGINE=InnoDB;";
        return $sql;
    }

    public function foreignKey(): string
    {
        $attributes = $this->getAttributes();
        $sqlParts = [];

        foreach ($attributes as $attribute) {

            if (!isset($attribute["constraint"], $attribute["fk"], $attribute["references"])) continue;
            
            $sql = "ALTER TABLE {$this->tableName} "
                . "ADD CONSTRAINT {$attribute["constraint"]} "
                . "FOREIGN KEY ({$attribute["fk"]}) "
                . "REFERENCES {$attribute["references"]}";

            if (!empty($attribute["onDelete"])) $sql .= " ON DELETE " . $attribute["onDelete"];
        
            if (!empty($attribute["onUpdate"])) $sql .= " ON UPDATE " . $attribute["onUpdate"];
        
            $sqlParts[] = $sql . ";";
        }

        return implode("\n", $sqlParts);
    }
}

