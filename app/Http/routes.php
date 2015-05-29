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
    Route::get('home', 'LayoutController@home');
    Route::get('/', ['as' => 'home', 'uses' => 'LayoutController@home']);

    // Products.
    Route::get('prod/{slug}', ['as' => 'product', 'uses' => 'ProductController@show']);

    // Cart & checkout.
    Route::get('cart', ['as' => 'cart', 'uses' => 'CheckoutController@index']);

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
    Route::get('brands/{id}',     ['as' => 'api.brands', 'uses' => 'ApiController@getBrand']);
    Route::get('categories/{id}', ['as' => 'api.categories', 'uses' => 'ApiController@getCategory']);
    Route::get('layouts/{id?}',   ['as' => 'api.layouts', 'uses' => 'ApiController@getLayout']);
    Route::get('products/{id}',   ['as' => 'api.products', 'uses' => 'ApiController@getProduct']);
    Route::get('search/{query}',  ['as' => 'api.search', 'uses' => 'ApiController@searchProducts']);

    Route::post('estimate',       ['as' => 'api.estimate', 'uses' => 'ApiController@getOrderEstimate']);
    Route::post('orders',         ['as' => 'api.orders', 'uses' => 'ApiController@placeOrder']);

    Route::any('/{catchAll}', function($catchAll) {
        return Illuminate\Http\JsonResponse::create(['status' => 400, 'error' => 'Bad request.'], 400);
    });
});

