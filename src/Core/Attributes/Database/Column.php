<?php 

namespace Choco\Core\Attributes\Database;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column 
{
    public function __construct(
        public string $type,
        public ?int $length = null,
        public bool $nullable = false,
        public mixed $default = null,
        public bool $unique = false,
        public ?string $name = null, //
    ){}
}