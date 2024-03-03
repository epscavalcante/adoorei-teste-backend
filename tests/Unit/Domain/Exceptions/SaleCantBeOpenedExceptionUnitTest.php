<?php

use Core\Domain\Exceptions\SaleCantBeOpenedException;
use Core\Domain\ValueObjects\Uuid;

test('SaleCantBeOpenedException', function () {
    throw new SaleCantBeOpenedException(new Uuid('0b055ef1-1ce8-4258-9e47-6cc2befdd454'));
})->throws(SaleCantBeOpenedException::class, "The sale (0b055ef1-1ce8-4258-9e47-6cc2befdd454) can't be opened.");
