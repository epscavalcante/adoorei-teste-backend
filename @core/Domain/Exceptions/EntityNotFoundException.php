<?php

namespace Core\Domain\Exceptions;

use Core\Domain\ValueObjects\Uuid;
use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct($namespace, Uuid $id)
    {
        $paths = explode('\\', $namespace);
        $className = end($paths);
        $message = "The {$className} ({$id->getValue()}) not found.";
        parent::__construct($message);
    }
}
