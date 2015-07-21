<?php

//dd(\Auth::user());
//dd(\App\User::all());

// Set all localized routes here.
Route::group([
    'prefix' => Localization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function() {

    // Homepage.
    Route::get('home',      'HomeController@index');
    Route::get('/',         ['as' => 'home', 'uses' => 'HomeController@index']);

    // Categories.
    Route::get('cat/{slug}.html',   ['as' => 'category', 'uses' => 'CategoryController@display']);

    // Products.
    Route::get('search',    ['as' => 'search', 'uses' => 'ProductController@search']);
    Route::get('prod/{slug}.html',  ['as' => 'product', 'uses' => 'ProductController@display']);

    // Cart & checkout.
    Route::get('cart',      ['as' => 'cart', 'uses' => 'CheckoutController@index']);

    // Custom pages.
    Route::get('pages/{slug}', ['as' => 'page', 'uses' => 'PagesController@display']);

    // Authentication routes.
    Route::get('login',     ['as' => 'auth.login', 'uses' => 'Auth\AuthController@getLogin']);
    Route::post('login',    ['as' => 'auth.login.action', 'uses' => 'Auth\AuthController@postLogin']);
    Route::get('logout',    ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@getLogout']);

    // Registration routes.
    Route::get('signup',    ['as' => 'auth.register', 'uses' => 'Auth\AuthController@getRegister']);
    Route::post('signup',   ['as' => 'auth.register.action', 'uses' => 'Auth\AuthController@postRegister']);

    Route::controllers([
//        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);

    // Wish list.
    Route::get("wishlist","WishlistController@index");


    //
    // Here, we try to catch some invalid URLs and redirect the user to the right page.
    //

    // Category pages should end with ".html"
    Route::get('cat/{slug}', function($slug) {
        return Redirect::to(route('category', ['slug' => $slug]));
    });

    // Product pages should end with ".html"
    Route::get('prod/{slug}', function($slug) {
        return Redirect::to(route('product', ['slug' => $slug]));
    });


    //
    // Temporary routes, used for development.
    //
    Route::group(['prefix' => 'dev'], function()
    {
        Route::get('list-customers', function() {
            return Illuminate\Support\Collection::make(Customers::all());
        });

        Route::get('get-customer', function() {
            return Illuminate\Support\Collection::make(Customers::get(base64_encode('a@a.com')));
        });

        Route::get('update-customer', function() {
            return Illuminate\Support\Collection::make(Customers::update(1357,
                'a@a.com',
                'Joey',
                'H1G 5F7',
                'es'
            ));
        });

        Route::get('store-info', function() {
            return Illuminate\Support\Collection::make(Store::info());
        });

        Route::get('error/{status}', function($status) {
            abort($status);
        });
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
    Route::get('orders/success',    ['as' => 'api.orders.success', 'uses' => 'ApiController@handleSuccessfulPayment']);
    Route::get('orders/failure',    ['as' => 'api.orders.failure', 'uses' => 'ApiController@handleFailedPayment']);
    Route::get('orders/cancel',     ['as' => 'api.orders.cancel', 'uses' => 'ApiController@handleCancelledPayment']);

    // Product endpoints.
    Route::get('products/{id}',     ['as' => 'api.products', 'uses' => 'ApiController@getProduct']);
    Route::get('search/{query}',    ['as' => 'api.search', 'uses' => 'ApiController@searchProducts']);
    Route::post('estimate',         ['as' => 'api.estimate', 'uses' => 'ApiController@getOrderEstimate']);


    // Return '400 Bad Request' on all other requests.
    Route::any('/{catchAll?}', function($catchAll = null) {
        return Illuminate\Http\JsonResponse::create(['status' => 400, 'error' => 'Bad request.'], 400);
    });
});

