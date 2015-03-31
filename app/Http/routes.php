<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization as Localization;

// Set all localized routes here.
Route::group(['prefix' => Localization::setLocale()], function()
{
    
    
    // Test routes
    Route::get('/', 'WelcomeController@index');
    Route::get('home', 'HomeController@index');
});

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

/**
 * Routes for testing the cart drawer and the cart checkout.
 */
Route::get("draw", "CheckoutController@draw");
Route::get("check", "CheckoutController@check");


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
