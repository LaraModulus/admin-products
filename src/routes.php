<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'admin/products',
    'middleware' => ['admin', 'auth.admin'],
    'namespace'  => 'LaraMod\Admin\Products\Controllers',
], function () {
    Route::get('/', ['uses' => 'ProductsController@index']);
    Route::group([
        'prefix' => 'items',
    ], function () {
        Route::get('/', ['as' => 'admin.products.items', 'uses' => 'ProductsController@index']);
        Route::get('/form', ['as' => 'admin.products.items.form', 'uses' => 'ProductsController@getForm']);
        Route::post('/form', ['as' => 'admin.products.items.form', 'uses' => 'ProductsController@postForm']);

        Route::get('/delete', ['as' => 'admin.products.items.delete', 'uses' => 'ProductsController@delete']);

        Route::get('/datatable', ['as' => 'admin.products.items.datatable', 'uses' => 'ProductsController@dataTable']);
    });

    Route::group([
        'prefix' => 'categories',
    ], function () {
        Route::get('/', ['as' => 'admin.products.categories', 'uses' => 'CategoriesController@index']);
        Route::get('/form', ['as' => 'admin.products.categories.form', 'uses' => 'CategoriesController@getForm']);
        Route::post('/form', ['as' => 'admin.products.categories.form', 'uses' => 'CategoriesController@postForm']);

        Route::get('/delete', ['as' => 'admin.products.categories.delete', 'uses' => 'CategoriesController@delete']);

        Route::get('/datatable',
            ['as' => 'admin.products.categories.datatable', 'uses' => 'CategoriesController@dataTable']);
    });

    Route::group([
        'prefix' => 'reviews',
    ], function () {
        Route::get('/', ['as' => 'admin.products.reviews', 'uses' => 'ReviewsController@index']);
        Route::get('/form', ['as' => 'admin.products.reviews.form', 'uses' => 'ReviewsController@getForm']);
        Route::post('/form', ['as' => 'admin.products.reviews.form', 'uses' => 'ReviewsController@postForm']);

        Route::get('/delete', ['as' => 'admin.products.reviews.delete', 'uses' => 'ReviewsController@delete']);
        Route::get('/datatable', ['as' => 'admin.products.reviews.datatable', 'uses' => 'ReviewsController@dataTable']);
    });

    Route::group([
        'prefix' => 'collections',
    ], function(){
        Route::get('/', ['as' => 'admin.products.collections', 'uses' => 'CollectionsController@index']);
        Route::get('/form', ['as' => 'admin.products.collections.form', 'uses' => 'CollectionsController@getForm']);
        Route::post('/form', ['as' => 'admin.products.collections.form', 'uses' => 'CollectionsController@postForm']);

        Route::get('/delete', ['as' => 'admin.products.collections.delete', 'uses' => 'CollectionsController@delete']);
        Route::get('/datatable', ['as' => 'admin.products.collections.datatable', 'uses' => 'CollectionsController@dataTable']);
    });

    Route::group([
        'prefix' => 'brands',
    ], function(){
        Route::get('/', ['as' => 'admin.products.brands', 'uses' => 'BrandsController@index']);
        Route::get('/form', ['as' => 'admin.products.brands.form', 'uses' => 'BrandsController@getForm']);
        Route::post('/form', ['as' => 'admin.products.brands.form', 'uses' => 'BrandsController@postForm']);

        Route::get('/delete', ['as' => 'admin.products.brands.delete', 'uses' => 'BrandsController@delete']);
        Route::get('/datatable', ['as' => 'admin.products.brands.datatable', 'uses' => 'BrandsController@dataTable']);
        Route::get('/autocomplete', ['as' => 'admin.products.brands.autocomplete', 'uses' => 'BrandsController@getAutocomplete']);
    });

    Route::group([
            'prefix' => 'options',
        ], function(){
            Route::get('/', ['as' => 'admin.products.options', 'uses' => 'OptionsController@index']);
            Route::get('/form', ['as' => 'admin.products.options.form', 'uses' => 'OptionsController@getForm']);
            Route::post('/form', ['as' => 'admin.products.options.form', 'uses' => 'OptionsController@postForm']);

            Route::get('/delete', ['as' => 'admin.products.options.delete', 'uses' => 'OptionsController@delete']);
            Route::get('/datatable', ['as' => 'admin.products.options.datatable', 'uses' => 'OptionsController@dataTable']);
        });

    Route::group([
            'prefix' => 'characteristics',
        ], function(){
            Route::get('/', ['as' => 'admin.products.characteristics', 'uses' => 'CharacteristicsController@index']);
            Route::get('/form', ['as' => 'admin.products.characteristics.form', 'uses' => 'CharacteristicsController@getForm']);
            Route::post('/form', ['as' => 'admin.products.characteristics.form', 'uses' => 'CharacteristicsController@postForm']);

            Route::get('/delete', ['as' => 'admin.products.characteristics.delete', 'uses' => 'CharacteristicsController@delete']);
            Route::get('/datatable', ['as' => 'admin.products.characteristics.datatable', 'uses' => 'CharacteristicsController@dataTable']);
        });

});
