<?php

use Core\Domain\Exceptions\SaleNotFoundException;
use Core\Domain\ValueObjects\Uuid;
use Tests\Unit\Core\Stubs\EntityStub;

test('SaleNotFoundExceptionUnitTest', function () {
    throw new SaleNotFoundException(new Uuid('0b055ef1-1ce8-4258-9e47-6cc2befdd454'));
})->throws(SaleNotFoundException::class, "The Sale (0b055ef1-1ce8-4258-9e47-6cc2befdd454) not found.");
