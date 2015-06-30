<?php

use \Localization;

// Set all localized routes here.
Route::group([
    'prefix' => Localization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function() {

    // Homepage.
    Route::get('home', 'LayoutController@home');
    Route::get('/', ['as' => 'home', 'uses' => 'LayoutController@home']);

    // Products.
    Route::get('prod/{slug}', ['as' => 'product', 'uses' => 'ProductController@show']);
    Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);

    // Cart & checkout.
    Route::get('cart', ['as' => 'cart', 'uses' => 'CheckoutController@index']);

    // Custom pages.
    Route::get('pages/{slug}', ['as' => 'page', 'uses' => 'PagesController@display']);

    // User authentication.
    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);

    // Temporary routes, used for development.
    Route::group(['prefix' => 'dev'], function()
    {
        /**
         * Routes for testing product page.
         */
        Route::get("prod/{slug}", "ProductController@show");

    });
});

// API endpoints.
Route::group(['prefix' => 'api'], function()
{
    Route::get('brands/{id}',     ['as' => 'api.brands', 'uses' => 'ApiController@getBrand']);
    Route::get('categories/{id}', ['as' => 'api.categories', 'uses' => 'ApiController@getCategory']);
    Route::get('layouts/{id?}',   ['as' => 'api.layouts', 'uses' => 'ApiController@getLayout']);
    Route::get('products/{id}',   ['as' => 'api.products', 'uses' => 'ApiController@getProduct']);
    Route::get('search/{query}',  ['as' => 'api.search', 'uses' => 'ApiController@searchProducts']);

    Route::post('estimate',       ['as' => 'api.estimate', 'uses' => 'ApiController@getOrderEstimate']);
    Route::post('orders',         ['as' => 'api.orders', 'uses' => 'ApiController@placeOrder']);
    Route::get('orders/{id}/{verification}',
        ['as' => 'api.orders.view', 'uses' => 'ApiController@getOrderDetails']);
    Route::get('orders/pay/{id}/{verification}',
        ['as' => 'api.orders.pay', 'uses' => 'ApiController@redirectToPaymentPage']);
    Route::get('orders/success',  ['as' => 'api.orders.success', 'uses' => 'ApiController@handleSuccessfulPayment']);
    Route::get('orders/failure',  ['as' => 'api.orders.failure', 'uses' => 'ApiController@handleFailedPayment']);
    Route::get('orders/cancel',   ['as' => 'api.orders.cancel', 'uses' => 'ApiController@handleCancelledPayment']);

    // Return '400 Bad Request' on all other requests.
    Route::any('/{catchAll}', function($catchAll) {
        return Illuminate\Http\JsonResponse::create(['status' => 400, 'error' => 'Bad request.'], 400);
    });
});

