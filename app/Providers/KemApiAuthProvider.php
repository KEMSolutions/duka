<?php namespace App\Providers;

use App\Models\User;
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
        $this->app['auth']->extend('kem', function()
        {
            return new KemApiUserProvider(new User);
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
