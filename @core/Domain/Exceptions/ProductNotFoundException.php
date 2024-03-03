<?php

namespace Core\Domain\Exceptions;

use Core\Domain\Entities\Product;
use Core\Domain\ValueObjects\Uuid;

class ProductNotFoundException extends EntityNotFoundException
{
    public function __construct(Uuid $productId)
    {
        parent::__construct(Product::class, $productId);
    }
}
