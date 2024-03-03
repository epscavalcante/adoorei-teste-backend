<?php

use Core\Domain\Exceptions\PriceNegativeValueException;

test('PriceNegativeValueExceptionUnitTest message', function () {
    throw new PriceNegativeValueException(-200);
})->throws(PriceNegativeValueException::class, 'The value (-200) must be grather than zero.');
