<?php

use \Localization;

// Set all localized routes here.
Route::group([
    'prefix' => Localization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function() {

    // User authentication (not implemented).
//    Route::controllers([
//        'auth' => 'Auth\AuthController',
//        'password' => 'Auth\PasswordController',
//    ]);

    // Homepage.
    Route::get('/', 'LayoutController@home');
    Route::get('home', 'LayoutController@home');

    // Products.
    Route::get("prod/{slug}", "ProductController@show");

    // Cart & checkout.
    Route::get("cart", "CheckoutController@index");

    // Temporary routes, used for development.
    Route::group(['prefix' => 'dev'], function()
    {
        // API tests.
        Route::get('get/{request}', function($request) {
            return Illuminate\Support\Collection::make(KemAPI::get($request, Input::all()));
        })->where('request', '.+');

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
Route::group(['prefix' => 'api', 'middleware' => 'csrf.any'], function()
{
    Route::get('brands/{id}',     'ApiController@getBrand');
    Route::get('categories/{id}', 'ApiController@getCategory');
    Route::get('layouts/{id?}',   'ApiController@getLayout');
    Route::get('products/{id}',   'ApiController@getProduct');
    Route::get('search/{query}',  'ApiController@searchProducts');
    Route::post('estimate',       'ApiController@getOrderEstimate');

    Route::any('/{catchAll}', function($catchAll) {
        return Illuminate\Http\JsonResponse::create(['status' => 400, 'error' => 'Bad request.'], 400);
    });
});

