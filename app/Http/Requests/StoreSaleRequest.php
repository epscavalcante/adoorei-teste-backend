<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="StoreSaleRequest",
 *     description="Body to create a sale",
 *     required=true,
 *     @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="products",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="productId",type="string", example="0006faf6-7a61-426c-9034-579f2cfcfa83"),
 *                     @OA\Property(property="price", type="int", example="1000"),
 *                     @OA\Property(property="amount", type="int", example="1"),
 *                 )
 *             ),
 *         )
 *     )
 * ),
 */
class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array|min:1',
            'products.*.productId' => 'required|uuid',
            'products.*.price' => 'required|int|min:0',
            'products.*.amount' => 'required|int|min:1'
        ];
    }
}
