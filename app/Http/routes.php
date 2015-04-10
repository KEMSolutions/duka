<?php

use \Localization;

// Set all localized routes here.
Route::group(['prefix' => Localization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function()
{
    // Authentication
    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);

    // Test routes
    Route::get('/', 'WelcomeController@index');
    Route::get('/welcome', 'WelcomeController@index');
    Route::get('home', 'HomeController@index');
    Route::group(['prefix' => 'dev'], function() {

        // API.
        Route::get('api/products/{id}', function($id) {
            return KemAPI::get('products/'. $id);
        });
        Route::get('api/{request?}', function($request = '') {
            return KemAPI::get($request);
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
         * Routes for testing homepage creation
         */
        Route::get("layout/home", "LayoutController@home");
    });
});

