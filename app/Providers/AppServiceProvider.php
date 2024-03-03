<?php

namespace App\Providers;

use App\Repositories\Eloquent\ProductEloquentRepository;
use App\Repositories\Eloquent\SaleEloquentRepository;
use Core\Application\Validations\IProductIdsExistsValidation;
use Core\Application\Validations\ProductIdsExistsValidation;
use Core\Domain\Repositories\IProductRepository;
use Core\Domain\Repositories\ISaleRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            IProductRepository::class,
            ProductEloquentRepository::class
        );

        $this->app->singleton(
            ISaleRepository::class,
            SaleEloquentRepository::class
        );

        $this->app->singleton(
            IProductIdsExistsValidation::class,
            ProductIdsExistsValidation::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
