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
        return parent::get('', ['embed' => 'blogs']);
    }

    /**
     * Retrieves the supported locales for the current store.
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
     * @deprecated
     * @param int $width
     * @param int $height
     * @param string $mode
     * @return string
     */
    public function logo($width = 200, $height = 200, $mode = 'fit') {
        return $this->squareLogo($width, $height, $mode, $force_bitmap=true);
    }

    /**
     * Gets the URL to the store's square logo. Will return a vector and ignore width, height and mode if a vector is available and force_bitmap is set to true.
     *
     * @param int $width
     * @param int $height
     * @param string $mode
     * @return string
     */
    public function squareLogo($width = 200, $height = 200, $mode = '', $force_bitmap=false){

        if (isset($this->info()->logos->square->vector) && $force_bitmap===false){
            return $this->info()->logos->square->vector->url;
        }

        return Utilities::setImageSizeAndMode($width, $height, $mode, $this->info()->logos->square->bitmap->url);

    }

    /**
     * Gets the URL to the store's rectangular logo. The rectangular logo will always be a vector or null if no rectangular logo is available
     *
     * @return string
     */
    public function rectangularLogo(){

        if (isset($this->info()->logos->rectangle->vector)){
            return $this->info()->logos->rectangle->vector->url;
        }

        return null;
    }

}
