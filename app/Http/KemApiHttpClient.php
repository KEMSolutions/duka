<?php namespace App\Http;

use Log;
use Cache;
use KemAPI;
use Carbon\Carbon;
use Localization;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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
    private $user  = '';

    /**
     * API user secret.
     */
    private $secret  = '';

    /**
     * API version.
     */
    const VERSION = 1;

    public function __construct($apiUser, $apiSecret, $config = [])
    {
        // Create our Guzzle HTTP client instance.
        $this->client = new Client($config);

        // Save API details.
        $this->user     = $apiUser;
        $this->secret   = $apiSecret;
    }

    /**
     * Makes a GET request to KEM's API.
     *
     * @param string $request       Request being made, e.g. "products/1234".
     * @param bool $returnResponse  Returns the response object if true, or the JSON-decoded object otherwise.
     * @throws \Exception           On invalid requests.
     * @return mixed                JSON-decoded response object or instance of \GuzzleHttp\Http\Response.
     */
    public function get($request, $returnResponse = false)
    {
        // Performance check.
        $request = preg_replace('/[^a-z0-9\/_]/i', '', $request);
        if (strlen($request) < 1) {
            throw new \Exception('Invalid API request.');
        }

        // Build endpoint URI.
        $uri = $this->endpoint .'/'. self::VERSION .'/'. $request;

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

            // Log error and display an error page.
            Log::error($e->getMessage());
            abort($e->getCode(), $e->getMessage());
        }

        // Return JSON object or instance of GuzzleHttp\Message\Response.
        return $returnResponse ? $response : json_decode($response->getBody()->getContents());
    }

    /**
     * Makes a HEAD request to KEM's API.
     *
     * @param string $request
     * @param bool $returnResponse
     * @throws \Exception
     */
    public function head($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
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
     * Makes a OPTIONS request to KEM's API.
     *
     * @param string $request
     * @param bool $returnResponse
     * @throws \Exception
     */
    public function options($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
    }

    /**
     * Shortcut to retrieve layouts for home page and cache the product details at the same time.
     *
     * @return object   Layouts object.
     */
    public function getHomePage()
    {
        // Retrieve layouts
        $layouts = Cache::remember('api.layouts', Carbon::now()->addMinutes(30), function() {
            return KemAPI::get('layouts');
        });

        // Cache products
        foreach ($layouts as $layout) {
            if (in_array($layout->type, ['mixed', 'featured'])) {
                $this->extractAndCache($layout->content->products, 'api.product.');
            }
        }

        return $layouts;
    }

    /**
     * Shortcut to retrieve the details for a given product.
     *
     * @param $id           ID of product to fetch from KEM's API.
     * @throws \Exception   On invalid product IDs.
     * @return object       Product object.
     */
    public function getProduct($id)
    {
        // Performance check.
        $id = (int) $id;
        if ($id < 1) {
            throw new \Exception('Invalid product identifier.');
        }

        // Retrieve product details
        $product = Cache::get('api.product.'. $id);
        if (!$product) {
            $product = $this->get('products/'. $id);
            Cache::put('api.product.'. $id, $product, Carbon::now()->addHours(3));
        }

        else {
            Log::info('Retrieved product "'. $product->id .'" from cache.');
        }

        return $product;
    }

    /**
     * Helper method to cache stuff
     *
     * @param array $list       Array of objects to be cached.
     * @param string $prepend   String to prepend to the cache key, e.g. "product.".
     * @param string $expires   Time at which cached objects should expire. Defaults to "Carbon::now()->addHours(3)".
     */
    private function extractAndCache($list, $prepend = '', $expires = null)
    {
        // Performance check.
        if (gettype($list) != 'array' && !($list instanceof Iterator)) {
            return;
        }

        // Cache each item in the list.
        $expires = $expires || Carbon::now()->addHours(3);
        foreach ($list as $item) {
            if (empty($item) || !isset($item->id) || empty($item->id)) {
                continue;
            }

            Cache::put($prepend . $item->id, $item, $expires);
            Log::info('Caching object with ID "'. $item->id .'" under the namespace "'. $prepend .'"');
        }
    }

    /**
     * Creates the signature string to be used for every request.
     *
     * @return string The signature for the current request.
     */
    private function getSignature()
    {
        // Collect data for signature
        $data = '' . $this->secret;

        // Create signature
        $sig = base64_encode(hash('sha512', $data, true));

        return $sig;
    }
}
