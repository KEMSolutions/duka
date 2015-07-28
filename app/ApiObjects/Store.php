<?php namespace App\ApiObjects;

use Cache;
use KemAPI;

use Carbon\Carbon;

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
        $contracts = Cache::remember('store.contracts', Carbon::now()->addWeek(), function()
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
        // Format mode.
        if (is_array($mode))
        {
            $modes = [];
            foreach ($mode as $key => $value) {
                $modes[] = is_integer($key) ? $value : $key .':'. $value;
            }

            $mode = implode(',', $modes);
        }

        return str_replace(['{width}', '{height}', '{mode}'], [$width, $height, $mode], $this->info()->logo->url);
    }
}

