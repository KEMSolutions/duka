<?php namespace App\Providers;

use \Config;
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
        $this->app->singleton('KemApiHttpClient', function() {

            $user = Config::get('services.kemapi.user', 0);
            $secret = Config::get('services.kemapi.secret', '');

            return new \App\Http\KemApiHttpClient($user, $secret, []);
        });
	}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'KemApiHttpClient',
        ];
    }

}
