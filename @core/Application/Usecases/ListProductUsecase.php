<?php

namespace Core\Application\Usecases;

use Core\Domain\Repositories\IProductRepository;

class ListProductUsecase
{
    protected IProductRepository $productRepository;

    public function __construct(IProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(): ListProductUsecaseOutput
    {
        $products = $this->productRepository->list();

        return ListProductUsecaseOutput::build(
            items: $products,
            total: count($products)
        );
    }
}
