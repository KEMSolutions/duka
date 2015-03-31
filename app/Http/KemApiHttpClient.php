<?php namespace App\Http;

use GuzzleHttp\Client;

class KemApiHttpClient
{
    /**
     * Guzzle HTTP client.
     */
    public $client;

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
     * @param string $request       Request being made.
     * @param bool $returnResponse  Whether to return the response object itself or the JSON-decoded object.
     * @return mixed                JSON-decoded response object.
     */
    public function get($request, $returnResponse = false)
    {
        // Build endpoint URI.
        // TODO: make sure URI makes sense...
        $uri = 'https://kemsolutions.com/CloudServices/index.php/api/'. self::VERSION .'/'. $request;

        // Make request.
        $response = $this->client->get($uri, [
            'headers' => [
                'X-Kem-User' => $this->user,
                'X-Kem-Signature' => $this->getSignature()
            ]
        ]);

        //
        return $returnResponse ? $response : json_decode($response->getBody()->getContents());
    }

    /**
     * Creates the signature string to be used for every request.
     *
     * @return string The signature for the current request.
     */
    public function getSignature()
    {
        // Collect data for signature
        $data = '' . $this->secret;

        // Create signature
        $sig = base64_encode(hash('sha512', $data, true));

        return $sig;
    }
}
