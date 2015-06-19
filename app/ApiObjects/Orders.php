<?php namespace App\ApiObjects;

use Log;
use Auth;
use Lang;
use Cache;
use KemAPI;
use Carbon\Carbon;

class Orders extends KemApiObject
{
    public function __construct() { parent::__construct('orders'); }

    public function get($id, $verification = null)
    {
        // Retrieve order details.
        $original = parent::get($id);

        // Check that user can view the order details.
        if (!Auth::check() && $verification != $original->verification) {
            abort(404, Lang::get('boukem.no_exist'));
        }

        // Remove sensitive information.
        $order = new \stdClass;
        $order->id = $original->id;
        $order->status = $original->status;
        $order->payment_details = new \stdClass;
        $order->payment_details->payment_url = $original->payment_details->payment_url;

        return $order;
    }

    /**
     * Retrieves shipping costs and delivery time estimates.
     *
     * @param array $products   Products to include in order.
     * @param array $address    Shipping address.
     * @return mixed
     */
    public function estimate(array $products, array $address)
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
            return $this->badRequest('Shipements to Canada must include a province code.');
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
            return Cache::get($key);
        }

        // Or cache new estimate.
        $estimate = KemAPI::post($this->baseRequest .'/estimate', $body);
        Cache::put($key, $estimate, Carbon::now()->addHour());

        return $estimate;
    }

    /**
     * @param $shippingDetails
     * @param $productList
     * @param $email
     * @param $shippingAddress
     * @param null $billingAddress
     * @return mixed
     */
    public function placeOrder($shippingDetails, $productList, $email, $shippingAddress, $billingAddress = null)
    {
        // Build request body.
        $data = new \stdClass;

        // Set return URLs.
        $data->success_url = route('api.orders.success');
        $data->failure_url = route('api.orders.failure');
        $data->cancel_url = route('api.orders.cancel');

        // Set order details.
        $data->email = $email;
        $data->shipping = $shippingDetails;
        $data->products = $productList;
        $data->shipping_address = $shippingAddress;
        if ($billingAddress) {
            $data->billing_address = $billingAddress;
        }

//        dd($data);
        $response = KemAPI::post($this->baseRequest, $data);
//        dd($response);

        // Check that response is not an error
        if (property_exists($response, 'error'))
        {
            Log::error($response->error);

            // TODO: Redirect to cart and display an error message.
            abort(404, Lang::get('boukem.error_occurred'));
        }

        return $response;
    }
}
