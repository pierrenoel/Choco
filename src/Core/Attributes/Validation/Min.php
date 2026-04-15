<?php 

namespace Choco\Core\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Min 
{
    public function __construct(
        public int $min,
        public ?string $message = null
    ){}
}