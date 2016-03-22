<?php

// Set all localized routes here.
Route::group([
    'prefix' => Localization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'csrf']],
    function() {

    // Homepage.
    Route::get('/',                 ['as' => 'home', 'uses' => 'HomeController@index']);

    // Contact page and ticket form
    Route::get('contact',      ['as' => 'contact', 'uses' => 'TicketController@index']);
    Route::post('contact',      ['as' => 'contact.store', 'uses' => 'TicketController@store']);


    // Other pages.
    Route::get('pages/{slug}',      ['as' => 'page', 'uses' => 'PageController@getPage']);
    Route::get('contracts/{slug}',  ['as' => 'contract', 'uses' => 'PageController@getContract']);

    // Blog
    Route::get('blog/rss', ["uses"=>'BlogController@getFeed', 'as'=>'blog.feed']);
    Route::resource('blog', 'BlogController');

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
    Route::get('reset/{token}',     ['as' => 'auth.reset.token', 'uses' => 'AccountController@getToken']);
    Route::post('reset',            ['as' => 'auth.reset.action', 'uses' => 'AccountController@postReset']);

    // Wish list.
    Route::get('wishlist',          ['as' => 'wishlist', 'uses' => 'WishlistController@index']);

    // Dynamic variables
    Route::get('dynamicjs/localizationsAndEndpoints.json', ['as' => 'localizationsAndEndpoints', 'uses' => 'HomeController@localizationsAndEndpoints']);


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
});

// Console webhooks endpoint
Route::post('webhooks', ['uses' => 'WebhooksController@postReceive', 'middleware' => ['validateWebhook']]);

// Console webhooks endpoint
Route::get('kiosk', ['uses' => 'KioskController@index']);


// API endpoints.
Route::group(['prefix' => 'api', 'middleware' => 'api.csrf'], function()
{
    // Set the locale if was sent through the request.
    Request::has('locale') ? Localization::setLocale(Request::input('locale', 'en')) : null;

    // Categories & Brands.
    Route::get('brands/{id?}',      ['as' => 'api.brands', 'uses' => 'ApiController@getBrand']);
    Route::get('categories/{id?}',  ['as' => 'api.categories', 'uses' => 'ApiController@getCategory']);

    // Locales.
    Route::get('locales/{id?}',     'ApiController@getLocales');

    // Product endpoints.
    Route::get('products/{id}',     ['as' => 'api.products', 'uses' => 'ApiController@getProduct']);
    Route::get('search/{query}',    ['as' => 'api.search', 'uses' => 'ApiController@searchProducts']);

    // Endpoints accessible through basic authentication.
    Route::group(['middleware' => 'auth.api'], function()
    {
        // Addresses.
        Route::get('addresses',         'ApiController@getAddresses');
        Route::get('addresses/{id}',    'ApiController@getAddress');
        Route::post('addresses',        'ApiController@createAddress');
        Route::put('addresses/{id}',    'ApiController@updateAddress');

        // Customers.
        Route::get('customers/{id?}',   'ApiController@getCustomer');

        // Orders endpoints.
        Route::post('estimate', ['as' => 'api.estimate', 'uses' => 'ApiController@getOrderEstimate']);
        Route::post('orders', ['as' => 'api.orders', 'uses' => 'ApiController@placeOrder']);
        Route::get('orders/{id}/{verification}', ['as' => 'api.orders.view', 'uses' => 'ApiController@getOrderDetails']);
        Route::get('orders/pay/{id}/{verification}', ['as' => 'api.orders.pay', 'uses' => 'ApiController@redirectToPaymentPage']);
        Route::get('orders/return', ['as' => 'api.orders.return', 'uses' => 'ApiController@returningFromPayment']);
    });

    // Return '400 Bad Request' on all other requests.
    Route::any('/{catchAll?}', function($catchAll = null) {
        return Illuminate\Http\JsonResponse::create(['status' => 400, 'error' => 'Bad request.'], 400);
    });
});

// Simili static files routes

Route::get("css/main.css", 'StaticController@getMainStylesheet');
Route::get('favicon.png', 'StaticController@getFavicon');
Route::get('apple-touch-icon.png', 'StaticController@getTouchIcon');
Route::get("robots.txt", 'StaticController@getRobots');
Route::get('favicon.ico', function(){
    return Redirect::to('/favicon.png', 301);
});
