<?php

namespace Core\Domain\ValueObjects;

use Core\Domain\Exceptions\PriceNegativeValueException;

class Price extends ValueObject
{
    public function __construct(
        protected int $valueInCents
    ) {
        $this->validate($valueInCents);
    }

    public function getValue(): int
    {
        return $this->valueInCents;
    }

    public function validate()
    {
        if ($this->valueInCents < 0) {
            throw new PriceNegativeValueException($this->valueInCents);
        }
    }
}
