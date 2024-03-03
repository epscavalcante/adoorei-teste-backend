<?php

use Core\Domain\Exceptions\UuidInvalidException;

test('UuidInvalidExceptionUnitTest message', function () {
    throw new UuidInvalidException('fake');
})->throws(UuidInvalidException::class, 'The value (fake) is not UUID valid');
