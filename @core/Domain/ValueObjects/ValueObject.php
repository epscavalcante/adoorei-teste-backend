<?php

namespace Core\Domain\ValueObjects;

abstract class ValueObject
{
    public function equals($object): bool
    {
        return is_a($object, self::class);
    }
}
