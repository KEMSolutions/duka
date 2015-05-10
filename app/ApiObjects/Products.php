<?php namespace App\ApiObjects;

use Log;
use Cache;
use KemAPI;
use Carbon\Carbon;

class Products extends KemApiObject
{
    public function __construct() { parent::__construct('products'); }

    /**
     * Searches KEM's database for products.
     *
     * @param string $query Search term.
     * @param int $page     The page to start from (see: https://developer.github.com/v3/#pagination).
     * @param int $perPage  The number of products to display per page (see: https://developer.github.com/v3/#pagination).
     * @return mixed        Search results.
     */
    public function search($query, $page = 1, $perPage = 40)
    {
        // Performance check
        $query = trim(strip_tags($query));
        if (strlen($query) < 1) {
            return $this->badRequest('Query too short.');
        }

        // Retrieve search results
        $key = $this->cacheNamespace .'.search.'. $query;
        if (!$results = Cache::get($key))
        {
            $results = KemAPI::get('products/search', [
                'q' => $query,
                'embed' => 'tags',
                'page' => $page,
                'per_page' => $perPage
            ]);

            // Check for errors.
            if (!$results || isset($results->error)) {
                return $results;
            }

            // Cache results and product details
            Log::info('Caching results for "'. $query .'" until '. Carbon::now()->addMinutes(15));
            Cache::put($key, $results, Carbon::now()->addMinutes(15));

            // Extract product details from results, and cache those too.
            $this->extractAndCache($results->organic_results, \Products::getCacheNamespace());
            foreach ($results->tags as $tag) {
                $this->extractAndCache($tag->products, \Products::getCacheNamespace());
            }
        }

        else {
            Log::info('Retrieving results for "'. $query .'" from cache.');
        }

        return $results;
    }
}
