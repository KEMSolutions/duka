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
    Route::get('home', 'LayoutController@home');
    Route::group(['prefix' => 'dev'], function() {

        // API.
        Route::get('api/{request}', function($request) {
            return Illuminate\Support\Collection::make(KemAPI::get($request, Input::all()));
        })->where('request', '.+');
        Route::get('home', function() {
            return KemAPI::getHomePage();
        });
        Route::get('brands/{id}', function($id) {
            return Illuminate\Support\Collection::make(Brands::get($id));
        });
        Route::get('categories/{id}', function($id) {
            return Illuminate\Support\Collection::make(Categories::get($id));
        });
        Route::get('layouts/{id?}', function($id = '') {
            return Illuminate\Support\Collection::make(Layouts::get($id));
        });
        Route::get('products/{id}', function($id) {
            return Illuminate\Support\Collection::make(Products::get($id));
        });
        Route::get('search/{query}', function($query) {
            return Illuminate\Support\Collection::make(KemAPI::search($query));
        });

        // Localization.
        Route::get('locale', function() {
            return Localization::getCurrentLocale();
        });

        /**
         * Routes for testing product page.
         * TODO: Franck! Search product by slug not by id!
         */
        Route::get("prod/{id}", "ProductController@show");
    });
});

