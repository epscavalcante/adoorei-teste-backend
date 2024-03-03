<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaleResource;
use Core\Application\Usecases\Sale\ListSaleUsecase;

class SaleController extends Controller
{
    public function list(ListSaleUsecase $usecase)
    {
        $output = $usecase->execute();

        return SaleResource::collection($output->items)->additional([
            'total' => $output->total
        ]);
    }
}
