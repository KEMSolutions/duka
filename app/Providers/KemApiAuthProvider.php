<?php namespace App\Providers;

use App\Models\Customer;
use App\Providers\KemApiUserProvider;
use Illuminate\Support\ServiceProvider;


class KemApiAuthProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Extend the Auth provider to support our custom KemApiUserProvider.
        $hash = $this->app['hash'];
        $this->app['auth']->extend('kemapi', function() use ($hash)
        {
            return new KemApiUserProvider($hash);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
