<?php namespace App\Http;

use Log;
use Cache;
use KemAPI;
use Localization;
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
     * @param mixed $body           Body of the request.
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

        // Make sure $body is a string.
        if (!is_string($body)) {
            $body = json_encode($body);
        }

        // Make API call and return results.
        return $this->makeRequest('POST', $uri, $body, $returnResponse);
    }

    /**
     * @param string $method        Request method.
     * @param string $endpoint      Full URI to API endpoint.
     * @param string $body          Body of request.
     * @param bool $returnResponse  Whether to return the response object itself instead of a JSON-decoded object.
     * @return mixed                JSON-decoded response object or instance of \GuzzleHttp\Http\Response.
     */
    protected function makeRequest($method, $endpoint, $body = '', $returnResponse = false)
    {
        // Build signature string.
        $sig = $body . $this->secret;
        $sig = base64_encode(hash('sha512', $sig, true));

        \Log::info("\n\n\tMaking API request...\n\tEndpoint: $endpoint\n\tBody: $body\n\tSignature: $sig\n\n");

        // Create request.
        $request = $this->client->createRequest($method, $endpoint, [
            'body' => $body,
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
//            return $returnResponse ? null : JsonResponse::create([
//                'status' => $e->getCode(),
//                'error' => $e->getMessage()
//            ])->getData();
            return $returnResponse ? null : (object) [
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ];
        }

        \Log::info("\n\n\tResponse:\n\n". var_export($response->json(['object' => true]), true) ."\n\n");

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
     * @deprecated
     */
    public function search($query, $page = 1, $perPage = 40)
    {
        return \Products::search($query, $page, $perPage);
    }

    /**
     * Shortcut to send a bad request status through JSON.
     *
     * @param string $msg   Optional message to pass on.
     * @return mixed        JSON object to be returned to response.
     */
    private function badRequest($msg = 'Bad Request.') {
//        return JsonResponse::create(['status' => 400, 'error' => $msg], 400)->getData();
        return ['status' => 400, 'error' => $msg];
    }
}
