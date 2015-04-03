<?php namespace App\Http;

use Log;
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
     * Performs a GET request.
     *
     * @param string $request       Request being made, e.g. "products/1234".
     * @param bool $returnResponse  Whether to return the response object itself or the JSON-decoded object.
     * @return mixed                JSON-decoded response object.
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

        // Return response.
        return $returnResponse ? $response : json_decode($response->getBody()->getContents());
    }

    public function post($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
    }

    public function put($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
    }

    public function patch($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
    }

    public function delete($request, $returnResponse = false)
    {


        throw new \Exception('501: Method not implemented.');
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
