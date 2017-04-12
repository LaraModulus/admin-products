<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin/products',
    'middleware' => ['web', 'auth'],
    'namespace' => 'Escapeboy\AdminProducts\Controllers',
], function(){
    Route::get('/', ['uses' => 'ProductsController@index']);
    Route::group([
        'prefix' => 'items'
    ], function(){
        Route::get('/', ['as' => 'admin.products.items', 'uses' => 'ProductsController@index']);
        Route::get('/form', ['as' => 'admin.products.items.form', 'uses' => 'ProductsController@getForm']);
        Route::post('/form', ['as' => 'admin.products.items.form', 'uses' => 'ProductsController@postForm']);

        Route::get('/delete', ['as' => 'admin.products.items.delete', 'uses' => 'ProductsController@delete']);
    });

    Route::group([
        'prefix' => 'categories'
    ], function(){
        Route::get('/', ['as' => 'admin.products.categories', 'uses' => 'CategoriesController@index']);
        Route::get('/form', ['as' => 'admin.products.categories.form', 'uses' => 'CategoriesController@getForm']);
        Route::post('/form', ['as' => 'admin.products.categories.form', 'uses' => 'CategoriesController@postForm']);

        Route::get('/delete', ['as' => 'admin.products.categories.delete', 'uses' => 'CategoriesController@delete']);
    });

    Route::group([
        'prefix' => 'reviews'
    ], function(){
        Route::get('/', ['as' => 'admin.products.reviews', 'uses' => 'ReviewsController@index']);
        Route::get('/form', ['as' => 'admin.products.reviews.form', 'uses' => 'ReviewsController@getForm']);
        Route::post('/form', ['as' => 'admin.products.reviews.form', 'uses' => 'ReviewsController@postForm']);

        Route::get('/delete', ['as' => 'admin.products.reviews.delete', 'uses' => 'ReviewsController@delete']);
    });


});
