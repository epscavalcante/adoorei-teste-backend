<?php

namespace Core\Domain\Entities;

use Core\Domain\ValueObjects\Price;
use Core\Domain\ValueObjects\Uuid;

class Product extends Entity
{
    private function __construct(
        public readonly string $name,
        public readonly string $description,
        private readonly Price $price,
        private readonly Uuid $productId,
    ) {
    }

    public static function create(
        string $name,
        string $description,
        int $price,
        ?string $productId = null
    ) {
        $id = $productId ?? Uuid::create()->getValue();

        return new Product(
            $name,
            $description,
            new Price($price),
            productId: new Uuid($id)
        );
    }

    public function getPrice()
    {
        return $this->price->getValue();
    }

    public function getId(): Uuid
    {
        return $this->productId;
    }
}
