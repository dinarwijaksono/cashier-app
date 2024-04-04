<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
