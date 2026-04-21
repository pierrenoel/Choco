<?php 

namespace Choco\Core\Attributes\Relation;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class OneToOne 
{
    public function __construct(
        public string $target
    ){}
}