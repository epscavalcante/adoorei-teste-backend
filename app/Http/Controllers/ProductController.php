<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Core\Application\Usecases\Product\ListProductUsecase;

/**
 * @OA\PathItem(path="/api/products")
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"/products"},
     *     summary="Get list of products",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="total",
     *                 type="int",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ProductResource"),
     *             ),
     *         )
     *     ),
     * )
     */
    public function list(ListProductUsecase $usecase)
    {
        $output = $usecase->execute();

        return ProductResource::collection($output->items)->additional([
            'total' => $output->total,
        ]);
    }
}
