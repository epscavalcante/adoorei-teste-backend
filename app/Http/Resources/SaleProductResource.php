<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="Sale Product resource",
 *     description="Sale product resource",
 *     @OA\Property(property="id", type="string", example="0006faf6-7a61-426c-9034-579f2cfcfa83"),
 *     @OA\Property(property="name", type="string", example="Produto de teste"),
 *     @OA\Property(property="amount", type="int", example="1"),
 *     @OA\Property(property="price", type="int", example="1000"),
 * )
 */
class SaleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->productId,
            'name' => $this->name,
            'price' => $this->price,
            'amount' => $this->amount,
        ];
    }
}
