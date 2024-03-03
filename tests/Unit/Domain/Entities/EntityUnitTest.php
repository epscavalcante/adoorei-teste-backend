<?php

use Core\Domain\ValueObjects\Uuid;
use Tests\Stubs\EntityStub;

describe('Entity unit test', function () {
    describe('getId method', function () {
        $uuid = Uuid::create();
        $entity = new EntityStub(
            id: $uuid
        );

        expect($entity->getId())->toBeInstanceOf(Uuid::class);
        expect($entity->getId()->getValue())->toBe($uuid->getValue());
    });
});
