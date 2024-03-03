<?php

namespace Core\Domain\Entities;

use Core\Domain\SaleStatus;
use Core\Domain\SaleStatusEnum;
use Core\Domain\SaleStatusFactory;
use Core\Domain\ValueObjects\Uuid;

class Sale extends Entity
{
    public SaleStatus $status;

    /**
     * @param  array<SaleProduct>  $products
     */
    private function __construct(
        private array $products,
        private readonly Uuid $saleId,
        private int $total,
        readonly SaleStatusEnum $statusEnum
    ) {
        $this->status = SaleStatusFactory::create($statusEnum, $this);
    }

    public static function create()
    {
        $saleId = Uuid::create();
        $status = SaleStatusEnum::OPENED;

        return new Sale(
            saleId: $saleId,
            products: [],
            total: 0,
            statusEnum: $status
        );
    }

    public static function restore(
        string $saleId,
        int $total,
        array $products,
        string $status
    ) {
        return new Sale(
            saleId: new Uuid($saleId),
            total: $total,
            statusEnum: SaleStatusEnum::create($status),
            products: $products,
        );
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getStatus(): SaleStatusEnum
    {
        return $this->status->value;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function markAsCancelled()
    {
        return $this->status->cancel();
    }

    public function isCancelled()
    {
        return $this->status->value->value === SaleStatusEnum::CANCELLED->value;
    }

    public function addProduct(SaleProduct $product): void
    {
        if (count($this->products) === 0) {
            $this->products[] = $product;
            $this->updateSaleTotal();

            return;
        }

        foreach ($this->products as $saleProduct) {

            if ($saleProduct->productId->equals($product->productId)) {
                $saleProduct->incrementAmount($product->getAmount());
                $this->updateSaleTotal();

                return;
            }
        }

        $this->products[] = $product;
        $this->updateSaleTotal();

    }

    /**
     * @param  array<SaleProduct>  $products
     */
    public function syncProducts(array $products): void
    {
        $this->products = [];

        if (count($products)) {
            foreach ($products as $product) {
                $this->addProduct($product);
            }
        }
        $this->updateSaleTotal();
    }

    public function getId(): Uuid
    {
        return $this->saleId;
    }

    private function updateSaleTotal()
    {
        $total = 0;

        foreach ($this->products as $product) {
            $total += $product->getTotal()->getValue();
        }

        $this->total = $total;
    }
}
