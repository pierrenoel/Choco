<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Choco\Core\App();
$app->run();


// use Choco\Core\Attributes\Column;
// use Choco\Entities\User;

// $reflection = new ReflectionClass(User::class);
// $properties = $reflection->getProperties();

// $array = [];

// foreach($properties as $property){
//     $attributes = $property->getAttributes(Column::class);
    
//     foreach($attributes as $attribute){
//         $field = $attribute->newInstance();

//         $array[$property->getName()] = [
//             "type" => $field->type,
//             "length" => $field->length,
//             "nullable" => $field->nullable
//         ];
//     }
// }

// var_dump($array);
