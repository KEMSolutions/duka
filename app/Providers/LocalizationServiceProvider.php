<?php namespace App\Providers;

use Cache;
use KemAPI;
use Carbon\Carbon;
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
        // Set supported locales for the Mcamara/Laravel-localization package.
        config(['laravellocalization.supportedLocales' => $this->getSupportedLocales()]);
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
        // Retrieve the list of supported locales from the cache. If the list
        // doesn't exist, retrieve it from KEM's API and cache it for next time.
        $expiresAt  = Carbon::now()->addWeek();
        $locales    = Cache::remember('supportedlocales', $expiresAt, function()
        {
            $supportedLocales = KemAPI::get('locales');
            foreach ($supportedLocales as $loc) {
                $locales[$loc->language] = [
                    'name' => $loc->language_name,
                    'native' => $loc->language_name,
                    'script' => 'Latn'
                ];
            }
            
            return $locales;
        });

        return $locales;
    }

}
