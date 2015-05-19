<?php namespace App\ApiObjects;

use App\Facades\Utilities;
use Log;
use Cache;
use KemAPI;
use Carbon\Carbon;

class Orders extends KemApiObject
{
    public function __construct() { parent::__construct('orders'); }

    /**
     * Retrieves shipping costs and delivery time estimates.
     *
     * @param array $products   Products to include in order.
     * @param string $address   Two-letter country code.
     * @return mixed
     */
    public function estimate(array $products, $address)
    {
        // Performance check.
        if (count($products) < 1 || strlen($address->country) != 2 || strlen($address->postcode) < 5) {
            return $this->badRequest('Invalid parameters [req: orders/estimate].');
        }

        // Prepare API request body.
        $body = new \stdClass;
        $body->products = [];
        $body->shipping_address = new \stdClass;
        $body->shipping_address->country = strtoupper($address->country);
        $body->shipping_address->postcode = strtoupper(preg_replace('/\s+/', '', $address->postcode));

        // Format product list.
        $products = (array) $products;
        foreach ($products as $product)
        {
            $std = new \stdClass;
            $std->id = (int) $product['id'];
            $std->quantity = isset($product['quantity']) ? (int) $product['quantity'] : 1;
            $body->products[] = $std;
        }

        // Retrieve estimate from cache.
        $key = json_encode($body);
        if (Cache::has($key)) {
            Log::info('Retrieving order estimate from cache...');
            return Cache::get($key);
        }

        // Or cache new estimate.
        $estimate = KemAPI::post($this->baseRequest .'/estimate', $body);
        Cache::put($key, $estimate, Carbon::now()->addHour());

        return $estimate;
    }

}
