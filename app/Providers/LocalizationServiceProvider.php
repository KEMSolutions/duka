<?php
namespace App\Providers;

use KemAPI;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

/**
 * Class LocalizationServiceProvider
 * @package App\Providers
 */
class LocalizationServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        // Set supported locales for the Mcamara/Laravel-localization package.
        if (app()->laravellocalization) {
            app()->laravellocalization->setSupportedLocales($this->getSupportedLocales());
        }
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {}

    /**
     * Retrieve the supported locales from KEM's API.
     *
     * @return mixed
     */
    public function getSupportedLocales()
    {
        // Format the store's supported locales for use with Mcamara\LaravelLocalization.
        // We do this silently (without caching the results) in case we're in a substore
        // (that would be determined later in the bootstrapping process).
        $supportedLocales = KemAPI::get('locales');

        $locales = [];
        foreach ($supportedLocales as $locale)
        {
            $locales[$locale->language] = [
                'name' => $locale->language_name,
                'script' => $locale->script,
                'native' => $locale->name
            ];
        }

        return $locales;
    }
}
