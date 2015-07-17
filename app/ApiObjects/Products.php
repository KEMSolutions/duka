<?php namespace App\ApiObjects;

use Log;
use Cache;
use KemAPI;
use Request;

use Carbon\Carbon;
use cebe\markdown\MarkdownExtra;

class Products extends BaseObject
{
    public function __construct(MarkdownExtra $parser)
    {
        parent::__construct('products');

        $this->markdown = $parser;
    }

    /**
     * Retrieves a product by ID or slug.
     *
     * @param mixed $id             ID or slug of product to fetch.
     * @param array $requestParams  Parameters to include in API request.
     * @return object               Product details.
     */
    public function get($id, $requestParams = [])
    {
        // Retrieve product details.
        $product = parent::get($id, $requestParams);
        if ($this->isError($product)) {
            return $product;
        }

        // Parse description.
        $product->localization->long_description = $this->markdown->parse($product->localization->long_description);

        return $product;
    }

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
            return Request::ajax() ? $this->badRequest('Query too short.') : null;
        } elseif ($perPage > 40 || $perPage < 1) {
            $perPage = 40;
        }

        // Retrieve search results
        $key = $this->cacheNamespace .'.search.'. $query;
        if (!$results = Cache::get($key))
        {
            // Make the API call.
            $response = KemAPI::get('products/search', [
                'q' => $query,
                'embed' => 'tags',
                'page' => $page,
                'per_page' => $perPage
            ], true);

            $results = json_decode($response->getBody());

            // Check for errors.
            if ($response->getStatusCode() != 200) {
                return $results;
            }

            // Retrieve the details related to this search, and save them in the search object.
//            $links = $response->getHeader('links');
            $results->total = $response->getHeader('x-total-count');

            // Cache results and product details
            Log::info('Caching results for "'. $query .'" until '. Carbon::now()->addMinutes(15));
            Cache::put($key, $results, Carbon::now()->addMinutes(15));

            // Extract product details from results, and cache those too.
            $this->extractAndCache($results->organic_results, \Products::getCacheNamespace());
            foreach ($results->tags as $tag) {
                $this->extractAndCache($tag->products, \Products::getCacheNamespace());
            }
        }

        return $results;
    }
}
