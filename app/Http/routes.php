<?php

use \Localization;

// Set all localized routes here.
Route::group([
    'prefix' => Localization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function() {

    // Homepage.
    Route::get('home', 'HomeController@index');
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

    // Products.
    Route::get('prod/{slug}', ['as' => 'product', 'uses' => 'ProductController@display']);
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

    // Categories.
    Route::get('cat/{slug}', ['as' => 'category', 'uses' => 'CategoryController@display']);

    // Temporary routes, used for development.
    Route::group(['prefix' => 'dev'], function()
    {

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

