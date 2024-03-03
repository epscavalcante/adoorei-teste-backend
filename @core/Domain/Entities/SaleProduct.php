<?php

namespace Core\Domain\Entities;

use Core\Domain\ValueObjects\Price;
use Core\Domain\ValueObjects\Uuid;

class SaleProduct
{
    public function __construct(
        public readonly Uuid $productId,
        // public readonly string $name,
        private Price $price,
        private int $amount,
    ) {
    }

    public static function create(
        string $productId,
        // string $name,
        int $price,
        int $amount,
        ?int $total = 0
    ) {
        return new SaleProduct(
            new Uuid($productId),
            // $name,
            new Price($price),
            $amount,
        );
    }

    public function getAmount(): int
    {
       return  $this->amount;
    }

    public function getPrice(): int
    {
       return  $this->price->getValue();
    }

    public function incrementAmount(int $amount)
    {
        $this->amount += $amount;
    }

    public function getTotal(): Price
    {
        $total = $this->amount * $this->price->getValue();

        return new Price($total);
    }
}
