<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFour();

        \Illuminate\Support\Facades\Auth::provider('eloquent_with_trashed', function ($app, array $config) {
            return new \App\Extensions\EloquentWithTrashedUserProvider($app['hash'], $config['model']);
        });
    }
}
