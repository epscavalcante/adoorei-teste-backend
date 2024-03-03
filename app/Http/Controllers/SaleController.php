<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Resources\SaleResource;
use Core\Application\Usecases\Sale\CancelSaleUsecase;
use Core\Application\Usecases\Sale\CancelSaleUsecaseInput;
use Core\Application\Usecases\Sale\CreateSaleUsecase;
use Core\Application\Usecases\Sale\CreateSaleUsecaseInput;
use Core\Application\Usecases\Sale\FindSaleUsecase;
use Core\Application\Usecases\Sale\FindSaleUsecaseInput;
use Core\Application\Usecases\Sale\ListSaleUsecase;
use Core\Application\Usecases\Sale\UpdateSaleUsecase;
use Core\Application\Usecases\Sale\UpdateSaleUsecaseInput;
use Illuminate\Http\Response;

/**
 * @OA\PathItem(path="/api/sales")
 */
class SaleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sales",
     *     tags={"/sales"},
     *     summary="Get list of sales",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
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
     *                 @OA\Items(ref="#/components/schemas/SaleResource"),
     *             ),
     *         )
     *     ),
     * )
     */
    public function list(ListSaleUsecase $usecase)
    {
        $output = $usecase->execute();

        return SaleResource::collection($output->items)->additional([
            'total' => $output->total
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/sales",
     *     tags={"/sales"},
     *     summary="Create a sale",
     *     operationId="createUsersWithListInput",
     *     @OA\RequestBody(ref="#/components/requestBodies/StoreSaleRequest"),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/SaleResource"),
     *     ),
     *     @OA\Response(response="400", description="Bad Request"),
     *     @OA\Response(response="422", description="Entity unprocessable"),
     * )
     */
    public function store(StoreSaleRequest $request, CreateSaleUsecase $usecase)
    {
        $input = new CreateSaleUsecaseInput(
            products: $request->validated('products')
        );
        $output = $usecase->execute($input);

        return response()->json((new SaleResource($output)), Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     tags={"/sales"},
     *     path="/api/sales/{id}",
     *     summary="Get an specific sale",
     *     @OA\Parameter(
     *         description="sale ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="An UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/SaleResource"),
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(FindSaleUsecase $usecase, $id)
    {
        $input = new FindSaleUsecaseInput($id);
        $output = $usecase->execute($input);

        return (new SaleResource($output))->response();
    }

    /**
     * @OA\Patch(
     *     tags={"/sales"},
     *     path="/api/sales/{id}/cancel",
     *     summary="Cancel a sale",
     *     @OA\Parameter(
     *         description="sale ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="Sale ID"),
     *     ),
     *     @OA\Response(response=204, description="No content"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function cancel(CancelSaleUsecase $usecase, $id)
    {
        $input = new CancelSaleUsecaseInput($id);
        $usecase->execute($input);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Put(
     *     path="/api/sales/{id}/products",
     *     tags={"/sales"},
     *     summary="Update product of sale",
     *     @OA\Parameter(
     *         description="sale ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="0006faf6-7a61-426c-9034-579f2cfcfa83", summary="An Uuid valid"),
     *     ),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateSaleRequest"),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/SaleResource")
     *     ),
     *     @OA\Response(response="400",description="Bad Request"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="422", description="Entity unprocessable"),
     * )
     */
    public function update(UpdateSaleRequest $request, UpdateSaleUsecase $usecase, $id)
    {
        $input = new UpdateSaleUsecaseInput(
            saleId: $id,
            products: $request->validated('products')
        );
        $output = $usecase->execute($input);

        return response()->json((new SaleResource($output)), Response::HTTP_OK);
    }
}
