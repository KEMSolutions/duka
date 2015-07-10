<?php namespace App\Http;

use Log;
use Cache;
use KemAPI;
use Localization;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class KemApiHttpClient
{
    /**
     * @var object  Instance of GuzzleHttp\Client.
     */
    public $client;

    /**
     * @var string  API endpoint.
     */
    private $endpoint = 'https://api.kem.guru/api';

    /**
     * @var string  API user ID.
     */
    private $user = '';

    /**
     * @var string  API user secret.
     */
    private $secret = '';

    /**
     * @constant    API version.
     */
    const VERSION = 1;

    /**
     * @var string  Current locale.
     */
    private $locale = 'en';

    /**
     * The constructor takes the API user and API secrets as strings, as well as other config options as an array.
     *
     * @param $apiUser      API user ID.
     * @param $apiSecret    API user secret.
     * @param array $config Configuration options for Guzzle.
     */
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
    public function get($request, $params = [], $returnResponse = false) {
        return $this->makeRequest('GET', $request, $params, '', $returnResponse);
    }

    /**
     * Makes a POST request to KEM's API.
     *
     * @param string $request       Request being made, e.g. "orders/estimate".
     * @param mixed $body           Body of the request.
     * @param bool $returnResponse  Whether to return the response object itself instead of a JSON-decoded object.
     * @return mixed                JSON-decoded response object or instance of \GuzzleHttp\Http\Response.
     */
    public function post($request, $body = '', $returnResponse = false) {
        return $this->makeRequest('POST', $request, [], $body, $returnResponse);
    }

    public function put($request, $body, $returnResponse = false) {
        return $this->makeRequest('PUT', $request, [], $body, $returnResponse);
    }

    /**
     * @param string $method        Request method.
     * @param string $endpoint      Request being made, e.g. "layouts".
     * @param array $params         Parameters to include with request.
     * @param string $body          Body of request.
     * @param bool $returnResponse  Whether to return the response object itself instead of a JSON-decoded object.
     * @return mixed                JSON-decoded response object or instance of \GuzzleHttp\Http\Response.
     */
    private function makeRequest($method, $request, $params = [], $body = '', $returnResponse = false)
    {
        // Performance check.
        if ($error = $this->checkRequest($request, $params, $body)) {
            Log::error($error);
            return $returnResponse ? null : $this->badRequest();
        }

        // Build endpoint URI.
        $endpoint = $this->endpoint .'/'. self::VERSION .'/'. $request;
        if (count($params)) {
            $endpoint .= '?'. http_build_query($params);
        }

        // Make sure $body is a string.
        if (!is_string($body)) {
            $body = json_encode($body);
        }

        // Build signature string.
        $sig = $body . $this->secret;
        $sig = base64_encode(hash('sha512', $sig, true));

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
            return $returnResponse ? null : (object) [
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ];
        }

        // Return an instance of GuzzleHttp\Message\Response or a JSON object.
        return $returnResponse ? $response : $response->json(['object' => true]);
    }

    private function checkRequest($request, $params, $body)
    {
        // Make sure we have a valid request.
        $request = preg_replace('/[^a-z0-9\/_\-=+]/i', '', $request);
        if (strlen($request) < 1) {
            return 'Invalid API request.';
        }

        // Check request parameters.
        if (!is_array($params)) {
            return 'Invalid parameters in API request.';
        }

        // Check request body.
        if (!is_string($body) && !is_object($body) && !is_array($body)) {
            return 'Invalid body in API request.';
        }

        return null;
    }

    /**
     * Shortcut to send a bad request status through JSON.
     *
     * @param string $msg   Optional message to pass on.
     * @return mixed        JSON object to be returned to response.
     */
    private function badRequest($msg = 'Bad Request.', $status = 400) {
//        return JsonResponse::create(['status' => 400, 'error' => $msg], 400)->getData();
        return ['status' => $status, 'error' => $msg];
    }
}
