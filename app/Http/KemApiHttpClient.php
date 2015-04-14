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
     * Instance of GuzzleHttp\Client.
     */
    public $client;

    /**
     * API endpoint.
     */
    private $endpoint = 'https://kemsolutions.com/CloudServices/index.php/api';

    /**
     * API user ID.
     */
    private $user = '';

    /**
     * API user secret.
     */
    private $secret = '';

    /**
     * API version.
     */
    const VERSION = 1;

    public function __construct($apiUser, $apiSecret, $config = [])
    {
        // Create our Guzzle HTTP client instance.
        $this->client = new Client($config);

        // Save API details.
        $this->user = $apiUser;
        $this->secret = $apiSecret;
    }

    /**
     * Makes a GET request to KEM's API.
     *
     * @param string $request       Request being made, e.g. "products/1234".
     * @param array $params         Parameters to include in request.
     * @param bool $returnResponse  Returns the response object if true, or the JSON-decoded object otherwise.
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

        // Make request.
        try{
            $response = $this->client->get($uri, [
                'headers' => [
                    'X-Kem-User' => $this->user,
                    'X-Kem-Signature' => $this->getSignature(),
                    'Accept-Language' => Localization::getCurrentLocale(),
                    'Content-Type' => 'application/json'
                ]
            ]);
        } catch (ClientException $e) {

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
     * Makes a DELETE request to KEM's API.
     *
     * @param string $request
     * @param bool $returnResponse
     * @throws \Exception
     */
    public function delete($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
    }

    /**
     * Makes a PUT request to KEM's API.
     *
     * @param string $request
     * @param bool $returnResponse
     * @throws \Exception
     */
    public function put($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
    }

    /**
     * Makes a PATCH request to KEM's API.
     *
     * @param string $request
     * @param bool $returnResponse
     * @throws \Exception
     */
    public function patch($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
    }

    /**
     * Makes a POST request to KEM's API.
     *
     * @param string $request
     * @param bool $returnResponse
     * @throws \Exception
     */
    public function post($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
    }

    /**
     * Shortcut to retrieve layouts for home page and cache the product details at the same time.
     *
     * @return mixed    JSON array with the home page's layouts.
     */
    public function getHomePage()
    {
        // Retrieve layouts.
        $layouts = Cache::remember('api.layouts', Carbon::now()->addMinutes(30), function() {
            return KemAPI::get('layouts');
        });

        if (!is_array($layouts) || isset($layouts->error)) {
            return $layouts;
        }

        // Cache products.
        foreach ($layouts as $layout) {
            if (in_array($layout->type, ['mixed', 'featured'])) {
                $this->extractAndCache($layout->content->products, 'api.products.');
            }
        }

        return $layouts;
    }

    /**
     * Shortcut to retrieve the details for a category and cache the product details at the same time.
     *
     * @param mixed $id     ID or slug of the category.
     * @param int $page     The page to start from (see: https://developer.github.com/v3/#pagination).
     * @param int $perPage  The number of products to display per page (see: https://developer.github.com/v3/#pagination).
     * @return object       JSON object for the specified category.
     */
    public function getCategory($id, $page = 1, $perPage = 40)
    {
        // Performance check.
        if ((is_numeric($id) && $id < 0) || preg_replace('/[^a-z0-9_-]/i', '', $id) != $id) {
            return $this->badRequest('Invalid category identifier.');
        }

        // Retrieve category details
        return $this->getAndCache($id, 'api.categories.', 'categories/'. $id, [
            'embed' => 'products',
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Shortcut to retrieve the details for a brand and cache the product details at the same time.
     *
     * @param mixed $id     ID or slug of the brand.
     * @param int $page     The page to start from (see: https://developer.github.com/v3/#pagination).
     * @param int $perPage  The number of products to display per page (see: https://developer.github.com/v3/#pagination).
     * @return mixed        JSON object for the specified brand.
     */
    public function getBrand($id, $page = 1, $perPage = 40)
    {
        // Performance check.
        if ((is_numeric($id) && $id < 0) || preg_replace('/[^a-z0-9_-]/i', '', $id) != $id) {
            return $this->badRequest('Invalid brand identifier.');
        }

        // Retrieve brand details.
        return $this->getAndCache($id, 'api.brands.', 'brands/'. $id, [
            'embed' => 'products',
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Shortcut to retrieve the details for a given product.
     *
     * @param mixed $id ID or slug of requested product.
     * @return object   JSON object for the specified product.
     */
    public function getProduct($id)
    {
        // Performance check.
        if ((is_numeric($id) && $id < 0) || preg_replace('/[^a-z0-9_-]/i', '', $id) != $id) {
            return $this->badRequest('Invalid product identifier.');
        }

        // Retrieve product details
        return $this->getAndCache($id, 'api.products.', 'products/'. $id);
    }

    /**
     * Helper method to retrieve objects and cache them (e.g. products, categories, etc.) If the object has products,
     * attempt to cache those as well.
     *
     * @param string $id            Object's ID or slug.
     * @param $namespace            String to prepend to the cache key, e.g. "api.products.".
     * @param string $request       API request to make if object isn't in the cache.
     * @param array $requestParams  Parameters to include with API request.
     * @param string $expires       Time at which cached objects should expire. Defaults to "Carbon::now()->addHours(3)".
     * @return mixed                Requested object.
     */
    public function getAndCache($id, $namespace, $request, $requestParams = [], $expires = null)
    {
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
                $this->extractAndCache($object->products, 'api.products.');
            }
        }

        else {
            Log::info('Retrieved "'. $namespace . $id .'" from cache.');
        }

        return $object;
    }

    /**
     * Helper method to cache stuff.
     *
     * @param array $list       Array of objects to be cached.
     * @param string $prepend   String to prepend to the cache key, e.g. "api.products.".
     * @param string $expires   Time at which cached objects should expire. Defaults to "Carbon::now()->addHours(3)".
     * @return void
     */
    public function extractAndCache($list, $prepend = '', $expires = null)
    {
        // Performance check.
        if (gettype($list) != 'array' && !($list instanceof Iterator)) {
            return;
        }

        // Cache each item in the list.
        $expires = $expires ?: Carbon::now()->addHours(3);
        foreach ($list as $item) {
            if (empty($item) || !isset($item->id) || empty($item->id) || Cache::has($prepend . $item->id)) {
                Log::info('Skipping object...');
                continue;
            }

            Log::info('Caching "'. $prepend . $item->id .'" until "'. $expires .'"');
            Cache::put($prepend . $item->id, $item, $expires);
            if (isset($item->slug) && strlen($item->slug)) {
                Cache::put($prepend . $item->slug, $item, $expires);
            }
        }
    }

    /**
     * Creates the signature string to be used for every request.
     *
     * @param string $body  Body of the request.
     * @return string       The signature for the current request.
     */
    private function getSignature($body = '')
    {
        // Collect data for signature
        $data = $body . $this->secret;

        // Create signature
        $sig = base64_encode(hash('sha512', $data, true));

        return $sig;
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
