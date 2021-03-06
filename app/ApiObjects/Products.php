<?php namespace App\ApiObjects;

use Log;
use Cache;
use KemAPI;
use Request;
use Utilities;

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
     * Retrieves a list of random products
     *
     * @return mixed               Random products.
     */
    public function random()
    {
        // Retrieve product details.
        $products = parent::all();
        foreach ($products as $product){
            // Parse description.
            $product->localization->long_description = $this->markdown->parse($product->localization->long_description);

            // Set images size.
            if (count($product->images) > 0) {
            $product->images[0]->thumbnail_lg = Utilities::setImageSizeAndMode(70, 110, 'fit', $product->images[0]->url);
            $product->images[0]->thumbnail = Utilities::setImageSizeAndMode(60, 60, 'fit', $product->images[0]->url);
            }
            else {
            $product->images[0]->thumbnail_lg = "https://static.boutiquekem.com/productimg-70-110-0000.png";
            $product->images[0]->thumbnail = "https://static.boutiquekem.com/productimg-60-60-0000.png";
            }
        }

        

        return $products;
    }


    /**
     * Retrieves a list of promoted products. 
     *
     * @return mixed promoted products
     */
    public function promoted()
    {
        
        $key = $this->cacheNamespace . "products_promoted";
        $promotedProducts = Cache::remember($key, Carbon::now()->addHours(1), function() {
            return $response = KemAPI::get('products/promoted', [], [], false);
        });
        $this->extractAndCache($promotedProducts, $this->getCacheNamespace());

        return $promotedProducts;
    }

    /**
     * Retrieves a list of promoted products. 
     *
     * @return mixed promoted products
     */
    public function featured()
    {
        
        $key = $this->cacheNamespace . "products_featured";
        $featuredProducts = Cache::remember($key, Carbon::now()->addHours(1), function() {
            return $response = KemAPI::get('products/featured', [], [], false);
        });
        $this->extractAndCache($featuredProducts, $this->getCacheNamespace());
        return $featuredProducts;
    }


    /**
     * Retrives a list of suggested products.Suggested
     *
     * @return mixed
     */
    public function suggested()
    {
        $key = $this->cacheNamespace . "products_suggested";
        $suggestedProducts = Cache::remember($key, Carbon::now()->addHours(1), function() {
            return $response = KemAPI::get('products/suggested', [], [], false);
        });
        $this->extractAndCache($suggestedProducts, $this->getCacheNamespace());
        return $suggestedProducts;
    }


    /**
     * Retrieves a product by ID or slug.
     *
     * @param mixed $id             ID or slug of product to fetch.
     * @param array $requestParams  Parameters to include in API request.
     * @param int $expires          Hours to keep object in cache.
     * @return object               Product details.
     */
    public function get($id, array $requestParams = [], $expires = 3)
    {
        // Retrieve product details.
        $product = parent::get($id, $requestParams, $expires);
        if ($this->isError($product)) {
            return $product;
        }

        // Parse description.
        if (isset($product->localization->long_description)) {
            $product->localization->long_description = $this->markdown->parse($product->localization->long_description);
        }

        // Parse usage.
        if (isset($product->localization->usage)) {
            $product->localization->usage = $this->markdown->parse($product->localization->usage);
        }

        // Parse warning.
        if (isset($product->localization->warning)) {
            $product->localization->warning = $this->markdown->parse($product->localization->warning);
        }

        // Parse content (list of ingredients).
        if (isset($product->localization->content)) {
            $product->localization->content = $this->markdown->parse($product->localization->content);
        }

        // Set images size.
        if (count($product->images) > 0) {
            $product->images[0]->thumbnail_lg = Utilities::setImageSizeAndMode(70, 110, 'fit', $product->images[0]->url);
            $product->images[0]->thumbnail = Utilities::setImageSizeAndMode(60, 60, 'fit', $product->images[0]->url);
        }
        else {
            $product->images[0]->thumbnail_lg = "https://static.boutiquekem.com/productimg-70-110-0000.png";
            $product->images[0]->thumbnail = "https://static.boutiquekem.com/productimg-60-60-0000.png";
        }

        return $product;
    }

    /**
     * Searches KEM's database for products.
     *
     * @param string $query         Search term.
     * @param int $page             The page to start from (see: https://developer.github.com/v3/#pagination).
     * @param int $perPage          The number of products to display per page (see: https://developer.github.com/v3/#pagination).
     * @param array $requestParams  Other parameters to include in API request.
     * @return mixed                Search results.
     */
    public function search($query, $page = 1, $perPage = 40, $requestParams = [])
    {
        // Performance check
        $query = trim(strip_tags($query));
        if (strlen($query) < 1) {
            return Request::ajax() ? $this->badRequest('Query too short.') : null;
        } elseif ($perPage > 40 || $perPage < 1) {
            $perPage = 40;
        }

        // Update request parameters.
        $requestParams['q'] = $query;
        $requestParams['page'] = (int) $page;
        $requestParams['per_page'] = $perPage;
        $requestParams['embed'] = isset($requestParams['embed']) ?: 'tags';

        // Retrieve search results
        $key = $this->cacheNamespace .'.search.'. $query .'.'. json_encode($requestParams);
        if (!$results = Cache::get($key))
        {
            // Make the API call.
            $response = KemAPI::get('products/search', $requestParams, [], true);

            $results = json_decode($response->getBody());

            // Check for errors.
            if ($response->getStatusCode() != 200) {
                return $results;
            }

            // Retrieve the details related to this search, and save them in the search object.
//            $links = $response->getHeader('links');
            $results->paginationTotal = $response->getHeader('x-total-count');

            // Cache results and product details
            Log::info('Caching results for "'. $query .'" until '. Carbon::now()->addMinutes(15));
            Cache::put($key, $results, Carbon::now()->addMinutes(15));

            // Extract product details from results, and cache those too.
            $this->extractAndCache($results->organic_results, $this->getCacheNamespace());
            if (property_exists($results, 'tags')) {
                foreach ($results->tags as $tag) {
                    $this->extractAndCache($tag->products, $this->getCacheNamespace());
                }
            }
        }

        return $results;
    }

    /**
     * Gets the product image according to dimensions and mode.
     *
     * @param $id
     * @param $width
     * @param $height
     * @param string $mode
     * @return mixed
     */
    public function getImage($id, $width, $height, $mode = "")
    {
        return Utilities::setImageSizeAndMode($width, $height, $mode, $this->get($id)->images[0]->url);
    }

    /**
     * Gets the product discount percent, as a nice displayable string.
     *
     * @param $format the format for wich you want the rebate percentage
     * @return string
     */
    public static function formatRebatePercent($format)
    {
        return round((($format->price - $format->reduced_price->price) * 100) / $format->price);
    }


}
