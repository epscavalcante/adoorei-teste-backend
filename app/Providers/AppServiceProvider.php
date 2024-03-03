<?php

namespace App\Providers;

use App\Repositories\Eloquent\ProductEloquentRepository;
use Core\Domain\Repositories\IProductRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
