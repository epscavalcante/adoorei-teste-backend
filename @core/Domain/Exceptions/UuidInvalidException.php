<?php

namespace Core\Domain\Exceptions;

use Exception;

class UuidInvalidException extends Exception
{
    public function __construct($value)
    {
        $message = "The value ({$value}) is not UUID valid";
        parent::__construct($message);
    }
}
