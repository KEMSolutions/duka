<?php namespace App\ApiObjects;

use Cache;
use KemAPI;
use Utilities;

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
     * TODO: use /locales endpoint instead of retrieving locales through store embeds.
     *
     * @return array
     */
    public function locales()
    {
        $locales = Cache::remember($this->getCacheKey('store.locales'), Carbon::now()->addWeek(), function()
        {
            $locales = [];
            $results = KemAPI::get('store', ['embed' => 'locales']);

            // Key languages by ID for easy retrieval.
            foreach ($results->locales as $locale) {
                $locales[$locale->id] = $locale;
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
        $contracts = Cache::remember($this->getCacheKey('contracts'), Carbon::now()->addWeek(), function()
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
    public function logo($width = 200, $height = 60, $mode = 'fit') {
        return Utilities::setImageSizeAndMode($width, $height, '', $this->info()->logo->url);
    }
}
