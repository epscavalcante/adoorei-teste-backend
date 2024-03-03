<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Core\Application\Usecases\Product\ListProductUsecase;

class ProductController extends Controller
{
    public function list(ListProductUsecase $usecase)
    {
        $output = $usecase->execute();

        return ProductResource::collection($output->items)->additional([
            'total' => $output->total
        ]);
    }
}
