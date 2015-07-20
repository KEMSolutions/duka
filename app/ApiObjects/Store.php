<?php namespace App\ApiObjects;

use Cache;
use KemAPI;

use Carbon\Carbon;
use App\Utilities\Utilities;

class Store extends BaseObject
{
    public function __construct() { parent::__construct('store'); }

    /**
     * Shortcut for self::get('').
     *
     * @return object   Store details.
     */
    public function info() {
        return parent::get('');
    }

    /**
     * Retrieves the supported locales for the current store.
     *
     * @return array
     */
    public function locales()
    {
        $locales = Cache::remember('store.locales', Carbon::now()->addDay(), function()
        {
            $results = KemAPI::get('store', ['embed' => 'locales']);

            // Format results for use with Mcamara\LaravelLocalization.
            foreach ($results->locales as $loc) {
                $locales[$loc->language] = [
                    'name' => $loc->language_name,
                    'native' => $loc->language_name,
                    'script' => $loc->script
                ];
            }

            return $locales;
        });

        return $locales;
    }

    /**
     * Retrieves the contract pages for the current store.
     *
     * @return mixed
     */
    public function contracts()
    {
        $contracts = Cache::remember($this->locale .'.store.contracts', Carbon::now()->addWeek(), function()
        {
            $results = KemAPI::get('store', ['embed' => 'contracts']);

            // Create an easily accessible array before caching it.
            $data = [];
            foreach ($results->contracts as $contract) {
                $data[$contract->slug] = $contract;
            }

            return $data;
        });

        return $contracts;
    }

    /**
     * Gets the URL to the stores logo.
     *
     * @param int $width
     * @param int $height
     * @param string $mode
     * @return string
     */
    public function logo($width = 200, $height = 60, $mode = 'fit')
    {
        $source = $this->info()->logo->url;
        return Utilities::setImageSizeAndMode($width, $height, $mode, $source);
    }
}

