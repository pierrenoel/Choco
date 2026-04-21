<?php 

namespace Choco\Core\Services\Entity;
use Choco\Core\Attributes\Database\Table;
use Choco\Core\Attributes\Relation\ManyToOne;
use Choco\Core\Services\Reflection;

class EntityService extends Reflection
{
    // Return an array like
    /*
         array:1 [▼
            "user" => "Choco\Entities\User"
            ]
    */
    public function getRelatedTables(string $table): array
    {
        $entities = readEntities();

        foreach ($entities as $entity) {

            $instanceEntity = new $entity();
            $class = new \ReflectionClass($instanceEntity);

            foreach ($class->getAttributes() as $attribute) {
                $classAttribute = $attribute->newInstance();

                if ($classAttribute->name === $table) {
                    return $this->extractRelations($class);
                }
            }
        }

        return [];
    }

    private function extractRelations(\ReflectionClass $class): array
    {
        $result = [];

        foreach ($class->getProperties() as $property) {

            $relationAttributes = $property->getAttributes(ManyToOne::class);

            foreach ($relationAttributes as $attribute) {
                $relation = $attribute->newInstance();

                $result[$property->getName()] = $relation->target;
            }
        }

        return $result;
    }

    // Method to get all the attributes (colunm)
    public function getEntityAttributes(string $class)
    {
        $reflection = new \ReflectionClass("Choco\\Entities\\{$class}");
        $properties = $reflection->getProperties();

        $result = [];

            foreach($properties as $property){
                foreach($property->getAttributes() as $attribute){
                    if($attribute->getName() == ManyToOne::class) continue;
                    $result[] = $property->getName();
                    }
            }

        return \array_unique($result);
    }

    // Get the this attribute #[Table('posts')] => posts
    public function getTableNameAttribute(mixed $class)
    {
        foreach($class as $key => $value){
            $class = new \ReflectionClass($value);
            foreach($class->getAttributes(Table::class) as $attribut){
                $instance = $attribut->newInstance();
                return $instance->name;
            }
        }
    }

    public function getTableNameFromClass(string $class): ?string
    {
        $reflection = new \ReflectionClass($class);

        $attributes = $reflection->getAttributes(Table::class);

        if (empty($attributes)) {
            return null;
        }

        $table = $attributes[0]->newInstance();

        return $table->name;
    }

    public function getQualifiedColumns(string $entity, string $alias): string
    {
        $attributes = $this->getEntityAttributes($entity);

        return implode(",", array_map(
            fn($col) => "{$alias}.{$col} AS {$alias}_{$col}",
            $attributes
        ));
    }

    public function hydrate(array $rows): array
    {
        $result = [];

        foreach ($rows as $row) {

            $item = [];

            foreach ($row as $key => $value) {

                if (str_contains($key, '_')) {

                    [$prefix, $field] = explode('_', $key, 2);

                    // clé principale (ex: post)
                    if (!isset($item[$prefix])) {
                        $item[$prefix] = [];
                    }

                    $item[$prefix][$field] = $value;

                } else {
                    $item[$key] = $value;
                }
            }

            $result[] = $item;
        }

        return $result;
    }
}