<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Resources\SaleResource;
use Core\Application\Usecases\Sale\CreateSaleUsecase;
use Core\Application\Usecases\Sale\CreateSaleUsecaseInput;
use Core\Application\Usecases\Sale\FindSaleUsecase;
use Core\Application\Usecases\Sale\FindSaleUsecaseInput;
use Core\Application\Usecases\Sale\ListSaleUsecase;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    public function list(ListSaleUsecase $usecase)
    {
        $output = $usecase->execute();

        return SaleResource::collection($output->items)->additional([
            'total' => $output->total
        ]);
    }

    public function store(StoreSaleRequest $request, CreateSaleUsecase $usecase)
    {
        $input = new CreateSaleUsecaseInput(
            products: $request->validated('products')
        );
        $output = $usecase->execute($input);

        return response()->json((new SaleResource($output)), Response::HTTP_CREATED);
    }

    public function show(FindSaleUsecase $usecase, $id)
    {
        $input = new FindSaleUsecaseInput($id);
        $output = $usecase->execute($input);

        return (new SaleResource($output))->response();
    }
}
