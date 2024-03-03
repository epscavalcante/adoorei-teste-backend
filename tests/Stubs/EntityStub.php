<?php

namespace Tests\Stubs;

use Core\Domain\Entities\Entity;
use Core\Domain\ValueObjects\Uuid;

class EntityStub extends Entity
{
    public function __construct(public Uuid $id)
    {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
