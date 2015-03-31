<?php namespace App\Providers;

use Cache;
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
            // TODO: move all Guzzle code to another class and use a facade or something...
            $client = new \GuzzleHttp\Client();
            $data   = '' . 'hLEQPVB9OduNPC5zd3ErIRs4e1wap0Dn9SEzUXeaMyovxJbowhC6TOSY4ySRel8';
            $sig    = base64_encode(hash('sha512', $data));
            dd($sig);

            $response = $client->get('https://kemsolutions.com/CloudServices/index.php/api/1/locales', [
                'headers' => ['X-Kem-User' => '1', 'X-Kem-Signature' => $sig]
            ]);
            
            dd($response);
            
            return [
                'en'    => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
                'en-CA' => ['name' => 'Canadian English', 'script' => 'Latn', 'native' => 'Canadian English'],
                'fr'    => ['name' => 'French', 'script' => 'Latn', 'native' => 'français'],
                'fr-CA' => ['name' => 'Canadian French', 'script' => 'Latn', 'native' => 'français canadien'],
            ];
        });

        return $locales;
    }

}
