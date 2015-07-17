<?php namespace App\Utilities;

use Localization;

class Utilities
{
    public function __construct()
    {
        // Build the cache namespace.
        $this->cacheNamespace = Localization::getCurrentLocale() .'.utilities.';
    }

    /**
     * Returns a list of countries, sorted by country code.
     *
     * @return array    Countries, sorted by code.
     */
    public function getCountryList() {
        return include __DIR__ .'/Countries/'. Localization::getCurrentLocale() .'.php';
    }
}

