<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class KemApiServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// Bind our KemApiHttpClient object to the service container.
        $this->app->bind('kemapihttpclient', function() {

            $user = '1';
            $secret = 'hLEQPVB9OduNPC5zd3ErIRs4e1wap0Dn9SEzUXeaMyovxJbowhC6TOSY4ySRel8';

            return new \App\Http\KemApiHttpClient($user, $secret, []);
        });
	}

}
