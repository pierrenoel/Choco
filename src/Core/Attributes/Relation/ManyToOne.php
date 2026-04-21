<?php 

namespace Choco\Core\Attributes\Relation;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ManyToOne 
{
    public function __construct(
        public string $target
    ){}
}