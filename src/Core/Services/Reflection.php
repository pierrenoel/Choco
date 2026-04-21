<?php 

namespace Choco\Core\Services;

abstract class Reflection
{
    public function getReflection(mixed $class) : \ReflectionClass
    {
        return new \ReflectionClass($class);
    }

    public function getAttributes(mixed $class) : array
    {
        $instance = $this->getReflection($class);
        return $instance->getAttributes();
    }

    public function getProperties(string $class) : array
    {
        $instance = $this->getReflection($class);

        $properties = $instance->getProperties();

        return $properties;
    }
}