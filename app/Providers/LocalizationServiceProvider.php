<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class LocalizationServiceProvider
 * @package App\Providers
 */
class LocalizationServiceProvider extends ServiceProvider {

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
		// Set supported locales for Mcamara/Laravel-localization.
        config(['laravellocalization.supportedLocales' => $this->getSupportedLocales()]);
	}

    public function getSupportedLocales()
    {
        // Make call to KEM API
        // ...

        return [
            'en'    => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
            'en-CA' => ['name' => 'Canadian English', 'script' => 'Latn', 'native' => 'Canadian English'],
            'fr'    => ['name' => 'French', 'script' => 'Latn', 'native' => 'français'],
            'fr-CA' => ['name' => 'Canadian French', 'script' => 'Latn', 'native' => 'français canadien'],
        ];
    }

}
