<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(ProductController::class)
    ->prefix('products')
    ->group(function () {
        Route::get('/', 'list')->name('products.list');
    });

Route::controller(SaleController::class)
    ->prefix('sales')
    ->group(function () {
        Route::get('/', 'list')->name('sales.list');
        Route::post('/', 'store')->name('sales.store');
        Route::get('{id}', 'show')->name('sales.show');
        Route::patch('{id}/cancel', 'cancel')->name('sales.cancel');
        Route::put('{id}/products', 'update')->name('sales.update_products');
    });
