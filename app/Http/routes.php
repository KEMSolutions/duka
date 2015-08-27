<?php

// Set all localized routes here.
Route::group([
    'prefix' => Localization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function() {

    // Homepage.
    Route::get('/',                 ['as' => 'home', 'uses' => 'HomeController@index']);

    // Other pages.
    Route::get('pages/{slug}',      ['as' => 'page', 'uses' => 'PageController@getPage']);
    Route::get('contracts/{slug}',  ['as' => 'contract', 'uses' => 'PageController@getContract']);

    // Categories.
    Route::get('cat/{slug}.html',   ['as' => 'category', 'uses' => 'CategoryController@display']);
    Route::get('brand/{slug}.html', ['as' => 'brand', 'uses' => 'BrandController@display']);

    // Products.
    Route::get('search',            ['as' => 'search', 'uses' => 'ProductController@search']);
    Route::get('prod/{slug}.html',  ['as' => 'product', 'uses' => 'ProductController@display']);

    // Cart & checkout.
    Route::get('cart',              ['as' => 'cart', 'uses' => 'CheckoutController@index']);

    // Customer routes.
    Route::get('login',             ['as' => 'auth.login', 'uses' => 'AccountController@getLogin']);
    Route::post('login',            ['as' => 'auth.login.action', 'uses' => 'AccountController@postLogin']);
    Route::get('logout',            ['as' => 'auth.logout', 'uses' => 'AccountController@getLogout']);

    Route::get('signup',            ['as' => 'auth.register', 'uses' => 'AccountController@getRegister']);
    Route::post('signup',           ['as' => 'auth.register.action', 'uses' => 'AccountController@postRegister']);

    Route::get('account',           ['as' => 'auth.account', 'uses' => 'AccountController@getAccount']);
    Route::post('account',          ['as' => 'auth.account.action', 'uses' => 'AccountController@postAccount']);

    Route::get('reset',             ['as' => 'auth.reset', 'uses' => 'AccountController@getReset']);
    Route::post('reset',            ['as' => 'auth.reset.action', 'uses' => 'AccountController@postReset']);

    // Wish list.
    Route::get('wishlist',          ['as' => 'wishlist', 'uses' => 'WishlistController@index']);


    //
    // Here, we try to catch some invalid URLs and redirect the user to the right page.
    //

    // There is no page called "home"
    Route::get('home', function() {
        return redirect(route('home'));
    });

    // Category pages should end with ".html"
    Route::get('cat/{slug}', function($slug) {
        return redirect(route('category', ['slug' => $slug]));
    });

    // Product pages should end with ".html"
    Route::get('prod/{slug}', function($slug) {
        return redirect(route('product', ['slug' => $slug]));
    });


    //
    // Temporary routes (used for development).
    //


    Route::group(['prefix' => 'dev'], function()
    {
        Route::get('list-customers', function() {
            return Illuminate\Support\Collection::make(Customers::all());
        });

        Route::get('get-customer', function() {
            return Illuminate\Support\Collection::make(Customers::get(base64_encode('kevin@kemsolutions.com')));
        });

        Route::get('store-info', function() {
            return Illuminate\Support\Collection::make(Store::info());
        });

        Route::get('error/{status}', function($status) {
            abort($status);
        });

        Route::get('categories/{id}', 'ApiController@getCategory');
    });
});

// API endpoints.
Route::group(['prefix' => 'api'], function()
{
    // Set locale.
    Request::has('locale') ? Localization::setLocale(Request::input('locale', 'en')) : null;

    // Category endpoints.
    Route::get('brands/{id}',       ['as' => 'api.brands', 'uses' => 'ApiController@getBrand']);
    Route::get('categories/{id}',   ['as' => 'api.categories', 'uses' => 'ApiController@getCategory']);

    // Layout endpoints.
    Route::get('layouts/{id?}',     ['as' => 'api.layouts', 'uses' => 'ApiController@getLayout']);

    // Orders endpoints.
    Route::post('orders',           ['as' => 'api.orders', 'uses' => 'ApiController@placeOrder']);
    Route::get('orders/{id}/{verification}', ['as' => 'api.orders.view', 'uses' => 'ApiController@getOrderDetails']);
    Route::get('orders/pay/{id}/{verification}',
        ['as' => 'api.orders.pay', 'uses' => 'ApiController@redirectToPaymentPage']);
    Route::get('orders/return',     ['as' => 'api.orders.return', 'uses' => 'ApiController@returningFromPayment']);

    // Product endpoints.
    Route::get('products/{id}',     ['as' => 'api.products', 'uses' => 'ApiController@getProduct']);
    Route::get('search/{query}',    ['as' => 'api.search', 'uses' => 'ApiController@searchProducts']);
    Route::post('estimate',         ['as' => 'api.estimate', 'uses' => 'ApiController@getOrderEstimate']);

    // Return '400 Bad Request' on all other requests.
    Route::any('/{catchAll?}', function($catchAll = null) {
        return Illuminate\Http\JsonResponse::create(['status' => 400, 'error' => 'Bad request.'], 400);
    });
});
