<?php namespace App\Utilities;

use Localization;

use Log;
use Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


class Utilities
{
    private $request;
    private $userCountryCode;

    public function __construct(Request $request)
    {
        // Build the cache namespace.
        $this->cacheNamespace = Localization::getCurrentLocale() .'.utilities.';

        // Save an instance of the request object.
        $this->request = $request;
    }

    /**
     * Returns a list of countries, sorted by country code.
     *
     * @return array    Countries, sorted by code.
     */
    public function getCountryList() {
        return include __DIR__ .'/Countries/'. Localization::getCurrentLocale() .'.php';
    }

    /**
     * Tries to guess the country code of the current user.
     *
     * @return string
     */
    public function getUserCountryCode()
    {
        return "FR";
        if (is_null($this->userCountryCode))
        {
            $this->userCountryCode = $this->request->headers->get('CF-IPCountry', 'CA');
        }

        return $this->userCountryCode;
    }

    /**
     * Will return the curency rate for the specified currency symbol (if available). 
     *
     * @param $currency the currency symbol needed for the price
     * @return string formatted price
     */
    public function getAlternateCurrencyRate($currency="EUR") {

        $expires = Carbon::now()->addHours(12);
        $key = "products_currencies";
        $rates = Cache::remember($key, $expires, function() {
            // Create a Guzzle HTTP client instance.
            $client = new Client();
            
            // Attempt to send request.
            $request = $client->createRequest("GET", "https://api.fixer.io/latest?base=CAD");
            try {
                $response = $client->send($request);
            } catch (ClientException $e) {
                // Log error.
                Log::error($e->getMessage());
                return null;
            }

            $responseJson = $response->json(['object' => false]);
            if (isset($responseJson["rates"])){
                return $responseJson["rates"];
            }
            
            return null;

        });
        
        $rate = isset($rates[$currency]) ? $rates[$currency] : null;
        return $rate;

    }

    public function currencyCodeForUser(){
        $userCountryCode = $this->getUserCountryCode();
        
        // Return a list of currencies for supported countries

        switch ($userCountryCode) {
            case 'CA':
                return "CAD";
            case 'US':
                return "USD";
            case 'MX':
                return "MXN";
            case 'GB':
                return "GBP";
            case 'FR':
            case 'BE':
            case 'CH':
            case 'ES':
            case 'DE':
            case 'IT':
            case 'LU':
            case 'PT':
                return "EUR";
            default:
                return null;
        }

    }

    /**
     * Returns a color brighter or darker according to a base color (hex formatted).
     *
     * Courtesy of http://stackoverflow.com/a/11951022
     * @param $hex
     * @param $steps
     * @return string
     */
    public function adjustBrightness($hex, $steps)
    {
        // Steps should be between -255 and 255. Negative = darker, positive = lighter
        $steps = max(-255, min(255, $steps));

        // Normalize into a six character long hex string
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2) . str_repeat(substr($hex,1,1), 2) . str_repeat(substr($hex,2,1), 2);
        }

        // Split into three parts: R, G and B
        $colorParts = str_split($hex, 2);
        $return = '' ;

        foreach ($colorParts as $color)
        {
            $color   = hexdec($color); // Convert to decimal
            $color   = max(0, min(255, $color + $steps)); // Adjust color
            $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
        }

        return $return;
    }

    /**
     * Returns an image path according to the width, height, mode and source entered as arguments.
     *
     * @param int|string $width     Desired width in pixels
     * @param int|string $height    Desired height in pixels
     * @param array|string $mode    An array or comma-separated list of filters.
     * @param string $source        Image URI.
     * @return string mixed
     */
    public function setImageSizeAndMode($width, $height, $mode, $source)
    {
        // Format mode.
        if (is_array($mode))
        {
            $modes = [];
            foreach ($mode as $key => $value) {
                $modes[] = is_integer($key) ? $value : $key .':'. $value;
            }

            $mode = implode(',', $modes);
        }

        return str_replace(['{width}', '{height}', '{mode}'], [$width, $height, $mode], $source);
    }
}

