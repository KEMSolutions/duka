<?php
namespace App\Providers;

use Carbon\Carbon;
use App\Http\KemApiHttpClient as ApiClient;
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
        // We do this silently without caching the results or initializing the KemAPI facade.
        $client = new ApiClient(config('services.kemapi.user'), config('services.kemapi.secret'));
        $supportedLocales = $client->get('locales');

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
