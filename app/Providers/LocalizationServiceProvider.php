<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        // Retrieve the list of supported locales from the cache. If the list doesn't exist,
        // retrieve it from KEM's API.
//        $expiresAt  = Carbon::now()->addWeek();
//        $locales    = $this->app['Cache']->remember('supportedlocales', $expiresAt, function()
//        {
//            return [
//                'en'    => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
//                'en-CA' => ['name' => 'Canadian English', 'script' => 'Latn', 'native' => 'Canadian English'],
//                'fr'    => ['name' => 'French', 'script' => 'Latn', 'native' => 'français'],
//                'fr-CA' => ['name' => 'Canadian French', 'script' => 'Latn', 'native' => 'français canadien'],
//            ];
//        });
//
//        return $locales;


        return [
            'en'    => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
            'en-CA' => ['name' => 'Canadian English', 'script' => 'Latn', 'native' => 'Canadian English'],
            'fr'    => ['name' => 'French', 'script' => 'Latn', 'native' => 'français'],
            'fr-CA' => ['name' => 'Canadian French', 'script' => 'Latn', 'native' => 'français canadien'],
        ];
    }

}
