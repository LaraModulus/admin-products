<?php

namespace LaraMod\AdminProducts;

use Illuminate\Support\ServiceProvider;

class AdminProductsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'adminproducts');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/laramod/admin-products'),
        ]);
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
    }
}
