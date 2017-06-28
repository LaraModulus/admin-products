<?php

namespace LaraMod\Admin\Products;

use Illuminate\Support\ServiceProvider;
use LaraMod\Admin\Core\Traits\DashboardTrait;
use LaraMod\Admin\Products\Controllers\ReviewsController;

class AdminProductsServiceProvider extends ServiceProvider
{
    use DashboardTrait;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'adminproducts');
        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/laramod/admin/products'),
        ]);
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        /**
         * Add reviews widget to controller
         */
        try{
            $this->addWidget($this->app->make(ReviewsController::class)->reviewsWidget());
        }catch (\Exception $e){
            $this->addWidget($e->getMessage());
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes.php';
    }
}
