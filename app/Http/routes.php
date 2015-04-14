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
    Route::get('home', 'HomeController@index');
    Route::group(['prefix' => 'dev'], function() {

        // API.
        Route::get('api/{request}', function($request) {
            return Illuminate\Support\Collection::make(KemAPI::get($request, Input::all()));
        })->where('request', '.+');
        Route::get('home', function() {
            return KemAPI::getHomePage();
        });
        Route::get('cat/{id}', function($id) {
            return Illuminate\Support\Collection::make(KemAPI::getCategory($id));
        });
        Route::get('prod/{id}', function($id) {
            return Illuminate\Support\Collection::make(KemAPI::getProduct($id));
        });

        // Localization.
        Route::get('locale', function() {
            return Localization::getCurrentLocale();
        });

        /**
         * Routes for testing the cart drawer and the cart checkout.
         */
        Route::get("draw", "CheckoutController@draw");
        Route::get("cart", "CheckoutController@cart");

        /**
         * Routes for testing the main layout elements (header, footer)
         */
        Route::get("layout/main", function() {
            return View("app");
        });

        /**
         * Routes for testing homepage creation.
         */
        Route::get("layout/home", "LayoutController@init");
    });
});

