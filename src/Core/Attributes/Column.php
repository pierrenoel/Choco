<?php 

namespace Choco\Core\Attributes;
use Attribute;

#[Attribute]
class Column 
{
    public function __construct(
        public string $type,
        public int $length,
        public bool $nullable
    ){}

}