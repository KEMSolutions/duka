<?php namespace App\Http;

use Log;
use Cache;
use KemAPI;
use Localization;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;

class KemApiHttpClient
{
    /**
     * @var mixed Instance of GuzzleHttp\Client.
     */
    public $client;

    /**
     * @var string API endpoint.
     */
    private $endpoint = 'https://kemsolutions.com/CloudServices/index.php/api';

    /**
     * @var string API user ID.
     */
    private $user = '';

    /**
     * @var string API user secret.
     */
    private $secret = '';

    /**
     * API version.
     */
    const VERSION = 1;

    /**
     * @var string Current locale.
     */
    private $locale = 'en';

    public function __construct($apiUser, $apiSecret, $config = [])
    {
        // Create our Guzzle HTTP client instance.
        $this->client = new Client($config);

        // Save API details.
        $this->user = $apiUser;
        $this->secret = $apiSecret;

        // Set current locale.
        $this->locale = Localization::getCurrentLocale();
    }

    /**
     * Makes a GET request to KEM's API.
     *
     * @param string $request       Request being made, e.g. "products/1234".
     * @param array $params         Parameters to include with request.
     * @param bool $returnResponse  Whether to return the response object itself instead of a JSON-decoded object.
     * @return mixed                JSON-decoded response object or instance of \GuzzleHttp\Http\Response.
     */
    public function get($request, $params = [], $returnResponse = false)
    {
        // Performance check.
        $request = preg_replace('/[^a-z0-9\/_-]/i', '', $request);
        if (strlen($request) < 1) {
            return $returnResponse ? null : $this->badRequest('Invalid API request.');
        }

        // Build endpoint URI.
        $uri = $this->endpoint .'/'. self::VERSION .'/'. $request;
        if (is_array($params) && !empty($params)) {
            $uri .= '?'. http_build_query($params);
        }

        // Make API call and return results.
        return $this->makeRequest('GET', $uri, '', $returnResponse);
    }

    /**
     * Makes a POST request to KEM's API.
     *
     * @param string $request       Request being made, e.g. "orders/estimate".
     * @param string $body          Body of the request.
     * @param bool $returnResponse  Whether to return the response object itself instead of a JSON-decoded object.
     * @return mixed                JSON-decoded response object or instance of \GuzzleHttp\Http\Response.
     */
    public function post($request, $body = '', $returnResponse = false)
    {
        // Performance check.
        $request = preg_replace('/[^a-z0-9\/_-]/i', '', $request);
        if (strlen($request) < 1) {
            return $returnResponse ? null : $this->badRequest('Invalid API request.');
        }

        // Build endpoint URI.
        $uri = $this->endpoint .'/'. self::VERSION .'/'. $request;

        // Make API call and return results.
        return $this->makeRequest('POST', $uri, $body, $returnResponse);
    }

    protected function makeRequest($method, $endpoint, $body = '', $returnResponse = false)
    {
        // Build signature string.
        $sig = $body . $this->secret;
        $sig = base64_encode(hash('sha512', $sig, true));

        // Create request.
        $request = $this->client->createRequest($method, $endpoint, [
            'headers' => [
                'X-Kem-User' => $this->user,
                'X-Kem-Signature' => $sig,
                'Accept-Language' => $this->locale,
                'Content-Type' => 'application/json'
            ]
        ]);

        // Attempt to send request.
        try {
            $response = $this->client->send($request);
        }

        // And catch any errors.
        catch (ClientException $e)
        {
            // Log error.
            Log::error($e->getMessage());
            return $returnResponse ? null : JsonResponse::create([
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ])->getData();
        }

        // Return an instance of GuzzleHttp\Message\Response or a JSON object.
        return $returnResponse ? $response : $response->json(['object' => true]);
    }

    /**
     * Shortcut to retrieve layouts for home page and cache the product details at the same time.
     *
     * @deprecated
     */
    public function getHomePage()
    {
        return \Layouts::get('');
    }

    /**
     * Shortcut to retrieve the details for a category and cache the product details at the same time.
     *
     * @deprecated
     */
    public function getCategory($id, $page = 1, $perPage = 40)
    {
        return \Categories::get($id, $page, $perPage);
    }

    /**
     * Shortcut to retrieve the details for a brand and cache the product details at the same time.
     *
     * @deprecated
     */
    public function getBrand($id, $page = 1, $perPage = 40)
    {
        return \Brands::get($id, $page, $perPage);
    }

    /**
     * Shortcut to retrieve the details for a given product.
     *
     * @deprecated
     */
    public function getProduct($id)
    {
        return \Products::get($id);
    }

    /**
     * Shortcut to search database for products.
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
        if (!$results = Cache::get($this->locale .'.api.search.'. $query)) {

            $results = $this->get('products/search', [
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
            Cache::put($this->locale .'.api.search.'. $query, $results, Carbon::now()->addMinutes(15));

            // Extract product details from results, and cache those too.
            $this->extractAndCache($results->organic_results);
            foreach ($results->tags as $tag) {
                $this->extractAndCache($tag->products);
            }
        }

        else {
            Log::info('Retrieving results for "'. $query .'" from cache.');
        }

        return $results;
    }

    /**
     * Helper method to retrieve objects and cache them (e.g. products, categories, etc.) If the object has products,
     * attempt to cache those as well.
     *
     * @param string $id            Object's ID or slug.
     * @param $namespace            String to prepend to the cache key, e.g. "en.api.products.".
     * @param string $request       API request to make if object isn't in the cache.
     * @param array $requestParams  Parameters to include with API request.
     * @param string $expires       Time at which cached objects should expire. Defaults to "Carbon::now()->addHours(3)".
     * @return mixed                Requested object.
     * @deprecated
     */
    public function getAndCache($id, $namespace, $request, $requestParams = [], $expires = null)
    {
        // Add common prefix to all namespaces.
        $namespace = $this->locale .'.api.'. $namespace;

        // Retrieve object from cache, or make an API call.
        if (!$object = Cache::get($namespace . $id)) {

            $object = $this->get($request, $requestParams);

            // Check for errors.
            if (!$object || isset($object->error)) {
                return $object;
            }

            // Cache object.
            $expires = $expires ?: Carbon::now()->addHours(3);
            Cache::put($namespace . $object->id, $object, $expires);
            Cache::put($namespace . $object->slug, $object, $expires);
            Log::info('Caching "'. $namespace . $object->id .'" until "'. $expires .'"');

            // Look for products to cache.
            if (isset($object->products) && count($object->products)) {
                Log::info('Attempting to cache products...');
                $this->extractAndCache($object->products);
            }
        }

        else {
            Log::info('Retrieved "'. $namespace . $id .'" from cache.');
        }

        return $object;
    }

    /**
     * Helper method to cache products from a list.
     *
     * @param array $list       Array of objects to be cached.
     * @param string $namespace String to prepend to the cache key, e.g. "en.api.products.".
     * @param string $expires   Time at which cached objects should expire. Defaults to "Carbon::now()->addHours(3)".
     * @return void
     * @deprecated
     */
    public function extractAndCache($list, $namespace = 'products.', $expires = null)
    {
        // Performance check.
        if (gettype($list) != 'array' && !($list instanceof Iterator)) {
            return;
        }

        // Add common prefix to all namespaces.
        $namespace = $this->locale .'.api.'. $namespace;

        // Cache each item in the list.
        $expires = $expires ?: Carbon::now()->addHours(3);
        foreach ($list as $item) {
            if (empty($item) || !isset($item->id) || empty($item->id) || Cache::has($namespace . $item->id)) {
                Log::info('Skipping object...');
                Log::info('Is empty? '. (empty($item) ? 'yes' : 'no'));
                Log::info('Is ID set? '. (isset($item->id) ? 'yes' : 'no'));
                Log::info('Is ID empty? '. (empty($item->id) ? 'yes' : 'no'));
                continue;
            }

            Log::info('Caching "'. $namespace . $item->id .'" until "'. $expires .'"');
            Cache::put($namespace . $item->id, $item, $expires);
            if (isset($item->slug) && strlen($item->slug)) {
                Cache::put($namespace . $item->slug, $item, $expires);
            }
        }
    }

    /**
     * Shortcut to send a bad request status through JSON.
     *
     * @param string $msg   Optional message to pass on.
     * @return mixed        JSON object to be returned to response.
     */
    private function badRequest($msg = 'Bad Request.') {
        return JsonResponse::create(['status' => 400, 'error' => $msg], 400)->getData();
    }
}
