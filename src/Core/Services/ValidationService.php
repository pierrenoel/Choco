<?php 

namespace Choco\Core\Services;

use Choco\Core\Request;

class ValidationService
{
    protected $request;
    protected array $rules = [];

    public function __construct(
        protected string $name
    ){
        $this->request = new Request();
    }

    public function execute()
    {
        $class = "Choco\\Entities\\{$this->name}";

        if (!class_exists($class)) {
            throw new \Exception("Entity {$class} not found");
        }

        $this->getRules($class);   
        return $this->executeRules();
    }

    private function getRules($entity)
    {
        $reflection = new \ReflectionClass($entity);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {

            $propertyName = $property->getName();

            // REQUIRED
            $this->affectRulesInArray( 
                $propertyName,
                $property->getAttributes(\Choco\Core\Attributes\Validation\Required::class),
                function($attribute){
                    $instance = $attribute->newInstance();
                    return [
                        "type" => (new \ReflectionClass($attribute->getName()))->getShortName(),
                        "message" => $instance->message,
                    ];
            });

            // MIN
            $this->affectRulesInArray( 
                $propertyName,
                $property->getAttributes(\Choco\Core\Attributes\Validation\Min::class),
                function($attribute){
                    $instance = $attribute->newInstance();
                    return [
                        "type" => (new \ReflectionClass($attribute->getName()))->getShortName(),
                        "min" => $instance->min,
                        "message" => $instance->message ?? null
                    ];
            });
           
        }

        return $this->rules;
    }

    private function affectRulesInArray(string $propertyName, array $attributes, callable $mapper)
    {
        foreach ($attributes as $attribute) {
            $this->rules[$propertyName][] = $mapper($attribute);
        }
    }

    private function executeRules(){

        $result = [];

        foreach ($this->rules as $field => $rules) {

            $value = $this->request->post($field);

            foreach ($rules as $rule) {

                $method = 'validate' . $rule["type"];

                if (method_exists($this, $method)) {
                    $error = $this->$method($value, $rule);

                    if ($error) {
                        $result[$field][] = $error;
                    }
                }
            }
        }

        return $result;
    }

    private function validateRequired($value, $rule)
    {
        if (empty($value)) {
            return $rule["message"];
        }
    }

    private function validateMin($value, $rule)
    {
        if (\strlen($value) < $rule["min"]) {
            return $rule["message"];
        }
    }
}




