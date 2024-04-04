<?php

namespace App\Providers;

use App\Repository\ItemStockRepository;
use App\Repository\ItemTransactionRepository;
use App\Repository\StockByPeriodRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(StockByPeriodRepository::class, function ($app) {
            return new StockByPeriodRepository($app);
        });

        $this->app->singleton(ItemTransactionRepository::class, function ($app) {
            return new ItemTransactionRepository($app);
        });

        $this->app->singleton(ItemStockRepository::class, function ($app) {
            return new ItemStockRepository($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
