<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Sale resource",
 *     description="Sale resource",
 *     @OA\Property(property="id", type="string", example="0006faf6-7a61-426c-9034-579f2cfcfa83"),
 *     @OA\Property(property="total", type="int", example="1000"),
 *     @OA\Property(property="status", type="string", example="opened"),
 *     @OA\Property(
 *          property="products",
 *          type="array",
 *          @OA\Items(ref="#/components/schemas/SaleProductResource")
 *     ),
 * )
 */
class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->saleId,
            'total' => $this->total,
            'status' => $this->status,
            'products' => SaleProductResource::collection($this->products),
        ];
    }
}
