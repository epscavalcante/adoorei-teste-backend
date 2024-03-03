<?php

use Core\Domain\Exceptions\EntityNotFoundException;
use Core\Domain\ValueObjects\Uuid;
use Tests\Stubs\EntityStub;

test('EntityNotFoundException - message', function () {
    $entity = new EntityStub(
        id: new Uuid('0b055ef1-1ce8-4258-9e47-6cc2befdd454')
    );
    throw new EntityNotFoundException(EntityStub::class, $entity->getId());
})->throws(EntityNotFoundException::class);
