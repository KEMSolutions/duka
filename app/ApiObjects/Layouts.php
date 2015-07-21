<?php namespace App\ApiObjects;

use Log;
use Cache;
use Products;
use Carbon\Carbon;

class Layouts extends BaseObject
{
    public function __construct() { parent::__construct('layouts'); }

    /**
     * Caches the layouts array.
     *
     * @param object $object    Layouts array.
     * @param string $requestID Layout name, or an empty string for the homepage.
     * @param object $expires   \Carbon\Carbon object representing when the cache should expire.
     */
    protected function cache($object, $requestID = 'home', $expires = null) {
        parent::cache($object, $requestID, $expires);
    }

    /**
     * Looks for products to cache within each layout.
     *
     * @param object $layouts
     */
    protected function findAndCache($layouts)
    {
        // Look for products to cache.
        foreach ($layouts as $layout) {
            if (isset($layout->content) && isset($layout->content->products)) {
                $this->extractAndCache($layout->content->products, Products::getCacheNamespace());
            }
        }
    }
}
