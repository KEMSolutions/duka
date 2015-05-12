<?php namespace App\Utilities;

class Utilities
{
    public function __construct()
    {
        // Build the cache namespace.
        $this->cacheNamespace = \Localization::getCurrentLocale() .'.utilities.';
    }

    public function getCountryList() {
        return include __DIR__ .'/Countries/'. \Localization::getCurrentLocale() .'.php';
    }
}
