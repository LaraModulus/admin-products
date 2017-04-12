<?php

namespace Escapeboy\AdminProducts;

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
            __DIR__.'/views' => base_path('resources/views/escapeboy/admin-products'),
        ]);
//        $this->publishes([
//            __DIR__.'/assets' => public_path('assets/escapeboy/dashboard'),
//        ], 'public');
//        $this->publishes([
//            __DIR__.'/../config/adminusers.php' => config_path('adminusers.php')
//        ], 'config');
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
