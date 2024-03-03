<?php

namespace Core\Application\Usecases;

use Core\Domain\Entities\Product;

class ListProductUsecaseOutput
{
    /**
     * @param array<ProductUsecaseOutput> $items
     */
    private function __construct(
        public readonly array $items,
        public readonly int $total
    ) {
    }

    /**
     * @param  array<Product>  $items
     */
    public static function build(array $items, int $total): self
    {
        $products = array_map(
            callback: fn ($product) => ProductUsecaseOutput::create($product),
            array: $items
        );

        return new self($products, $total);
    }
}
