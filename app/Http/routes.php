<?php

use \Localization;

// Set all localized routes here.
Route::group(['prefix' => Localization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function()
{
    // Authentication.
    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);

    // Test routes.
    Route::get('/', 'WelcomeController@index');
    Route::get('/welcome', 'WelcomeController@index');
    Route::get('home', 'LayoutController@home');
    Route::group(['prefix' => 'dev'], function() {

        // API tests.
        Route::get('get/{request}', function($request) {
            return Illuminate\Support\Collection::make(KemAPI::get($request, Input::all()));
        })->where('request', '.+');
        Route::get('home', function() {
            return Layouts::get('');
        });
        Route::get('brands/{id}', function($id) {
            return Illuminate\Support\Collection::make(Brands::get($id));
        });
        Route::get('categories/{id}', function($id) {
            return Illuminate\Support\Collection::make(Categories::get($id));
        });
        Route::get('layouts/{id?}', function($id = '') {
            return Illuminate\Support\Collection::make(Layouts::get($id));
        });
        Route::get('products/{id}', function($id) {
            return Illuminate\Support\Collection::make(Products::get($id));
        });
        Route::get('search/{query}', function($query) {
            return Illuminate\Support\Collection::make(Products::search($query));
        });
        Route::get('estimate', function() {
            return Illuminate\Support\Collection::make(Orders::estimate([
                ['id' => 616],
                ['id' => 95, 'quantity' => 3],
            ], 'CA', 'H2V 4G7'));
        });
        Route::get('countries', function() {
            return Utilities::getCountryList();
        });

        /**
         * Routes for testing product page.
         */
        Route::get("prod/{slug}", "ProductController@show");

        /**
         * Route for testing checkout
         */
        Route::get("cart", "CheckoutController@index");
    });
});

// API endpoints.
Route::group(['prefix' => 'api'], function()
{
    Route::get('brands/{id}',     'ApiController@getBrand');
    Route::get('categories/{id}', 'ApiController@getCategory');
    Route::get('layouts/{id?}',   'ApiController@getLayout');
    Route::get('products/{id}',   'ApiController@getProduct');
    Route::get('search/{query}',  'ApiController@searchProducts');
    Route::post('estimate',       'ApiController@getOrderEstimate');

    // Temporary catch-all
    Route::get('/{catchAll}', function($catchAll) {
        return $catchAll;
    });
});

