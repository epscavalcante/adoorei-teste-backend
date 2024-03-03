<?php

namespace Core\Application\Usecases;

use Core\Domain\Entities\Product;

class ProductUsecaseOutput
{
    /**
     * @param  array<ProductUsecaseOutput> $items
     */
    private function __construct(
        public readonly string $productId,
        public readonly string $name,
        public readonly string $description,
        public readonly int $price,
    ) {
    }

    public static function create(Product $product): self
    {
        return new self(
            productId: $product->getId()->getValue(),
            name: $product->name,
            description: $product->description,
            price: $product->getPrice(),
        );
    }
}
