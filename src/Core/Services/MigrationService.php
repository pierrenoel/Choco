<?php 

namespace Choco\Core\Services;

use Choco\Core\Attributes\AutoIncrement;
use Choco\Core\Attributes\Column;
use Choco\Core\Attributes\Id;
use Choco\Core\Attributes\Table;

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
                    "name" => $field->name,
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
        }

        return $this->result;
    }

    public function createTable()
    {
        $attributes = $this->getAttributes();

        $sql = "CREATE TABLE IF NOT EXISTS " . $this->tableName . " (";

        foreach($attributes as $name => $attribute){
            $sql .= $name . " " . strtoupper($attribute["type"]);

            if (isset($attribute["length"])) {
                $sql .= "(" . $attribute["length"] . ")";
            }

            if (isset($attribute["nullable"]) && !$attribute["nullable"]) {
                $sql .= " NOT NULL";
            }

            if (isset($attribute["default"])) {
                $sql .= " DEFAULT '" . $attribute["default"] . "'";
            }

            if (isset($attribute["unique"]) && $attribute["unique"]) {
                $sql .= " UNIQUE";
            }

            if (isset($attribute["primaryKey"]) && $attribute["primaryKey"]) {
                $sql .= " PRIMARY KEY";
            }

            if (isset($attribute["autoIncrement"]) && $attribute["autoIncrement"]) {
                $sql .= " AUTO_INCREMENT";
            }

            $sql .= ", ";
        }

        // Remove the last comma and space
        $sql = rtrim($sql, ", ") . ") ENGINE=InnoDB;";

        return $sql;
    }

}

