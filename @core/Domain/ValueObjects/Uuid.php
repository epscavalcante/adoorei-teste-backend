<?php

namespace Core\Domain\ValueObjects;

use Core\Domain\Exceptions\UuidInvalidException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid extends ValueObject
{
    public function __construct(
        private string $value
    ) {
        $this->validate($value);
    }

    public function equals($uuid): bool
    {
        return $this->getValue() === $uuid->getValue();
    }

    public static function create()
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }

    public function validate()
    {
        if (!RamseyUuid::isValid($this->value))
            throw new UuidInvalidException($this->value);
    }
}
