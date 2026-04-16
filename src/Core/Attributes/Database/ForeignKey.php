<?php 

namespace Choco\Core\Attributes\Database;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ForeignKey {
    public function __construct(
        public string $entity,
        public ?string $onDelete = null,
        public ?string $onUpdate = null
    ){}
}