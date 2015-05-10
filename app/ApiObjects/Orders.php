<?php namespace App\ApiObjects;

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
     * @param array $products       Products to include in order.
     * @param string $country       Two-letter country code.
     * @param string $postalCode    Postal or ZIP code.
     * @return mixed
     */
    public function estimate(array $products, $country, $postalCode)
    {
        // Performance check.
        if (count($products) < 1 || strlen($country) != 2 || strlen($postalCode) < 5) {
            return $this->badRequest('Invalid parameters [req: orders/estimate].');
        }

        // ...
        $body = new \stdClass;
        $body->products = [];
        $body->shipping_address = new \stdClass;
        $body->shipping_address->country = $country;
        $body->shipping_address->postcode = $postalCode;

        // Format product list.
        foreach ($products as $product)
        {
            $std = new \stdClass;
            $std->id = (int) $product['id'];
            $std->quantity = isset($product['quantity']) ? (int) $product['quantity'] : 1;
            $body->products[] = $std;
        }

        return KemAPI::post($this->baseRequest .'/estimate', $body);
    }

}
