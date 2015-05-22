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
     * @param array $address    Shipping address.
     * @return mixed
     */
    public function estimate(array $products, array $address, $email)
    {
        // Performance check.
        if (count($products) < 1 || !isset($address['country']) || !isset($address['postcode'])) {
            Log::info('Invalid parameters for order estimate.');
            return $this->badRequest('Invalid parameters.');
        }

        // Validate some inputs, to avoid unnecessary strain on the main server.
        $address['country'] = preg_replace('/[^A-Z]/', '', strtoupper($address['country']));
        $address['province'] = preg_replace('/[^A-Z]/', '', strtoupper(@$address['province']));
        $address['postcode'] = preg_replace('/[^A-Z0-9- ]/', '', strtoupper($address['postcode']));
        if (strlen($address['country']) != 2 || strlen($address['postcode']) < 5) {
            Log::info('Invalid address for order estimate.');
            return $this->badRequest('Invalid parameters.');
        } elseif ($address['country'] == 'CA' && strlen($address['province']) != 2) {
            Log::info('Invalid province code for order estimate.');
            return $this->badRequest('Invalid parameters.');
        }

        // Prepare API request body.
        $body = new \stdClass;
        $body->products = [];
        $body->shipping_address = new \stdClass;
        $body->shipping_address->country = $address['country'];
        $body->shipping_address->postcode = $address['postcode'];
        if ($address['country'] == 'CA') {
            $body->shipping_address->province = $address['province'];
        }

        // Format product list.
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
