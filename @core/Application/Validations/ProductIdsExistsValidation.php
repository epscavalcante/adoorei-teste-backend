<?php

namespace Core\Application\Validations;

use Core\Domain\Exceptions\ProductNotFoundException;
use Core\Domain\Repositories\IProductRepository;
use Core\Domain\ValueObjects\Uuid;

class ProductIdsExistsValidation implements IProductIdsExistsValidation
{

    public function __construct(
        private readonly IProductRepository $productRepository
    ) {
    }

    public function validate(array $productIds): array
    {
        $productIds = array_map(fn ($id) => new Uuid($id), $productIds);
        $result = $this->productRepository->existsByIds($productIds);

        return [
            'exists' => $result['exists'],
            'notExists' => count($result['notExists']) ?  array_map(fn ($id) => new ProductNotFoundException($id), $result['notExists']) : []
        ];
    }
}
