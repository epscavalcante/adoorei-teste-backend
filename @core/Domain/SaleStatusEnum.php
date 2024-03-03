<?php

namespace Core\Domain;

use Exception;

enum SaleStatusEnum: string
{
    case OPENED = 'opened';
    case CANCELLED = 'cancelled';

    public static function create(string $status)
    {
        if (! self::isValid($status)) {
            throw new Exception("The status {$status} is inválid");
        }

        return self::tryFrom($status);
    }

    public static function isValid(string $status): bool
    {
        foreach (self::cases() as $case) {
            if ($case->value === $status) {
                return true;
            }
        }

        return false;
    }
}
