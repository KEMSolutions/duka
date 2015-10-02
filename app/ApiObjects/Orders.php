<?php namespace App\ApiObjects;

use Log;
use Auth;
use Lang;
use Cache;
use KemAPI;

use Carbon\Carbon;
use App\Models\Customer;

class Orders extends BaseObject
{
    public function __construct() { parent::__construct('orders'); }

    /**
     * Retrieves the details of an existing order.
     *
     * @param int $id           Order ID.
     * @param int $verification Order verification code.
     * @return object
     */
    public function details($id, $verification = null)
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
     * @param array $items      Products to include in order.
     * @param array $address    Shipping address.
     * @return mixed
     */
    public function estimate(array $items, array $address)
    {
        // Performance check.
        if (count($items) < 1 || !isset($address['country']) || !isset($address['postcode'])) {
            Log::error('Invalid parameters for order estimate.');
            return $this->badRequest('Invalid parameters.');
        }

        // Validate some inputs before making request.
        $address['country'] = preg_replace('/[^A-Z]/', '', strtoupper($address['country']));
        $address['province'] = preg_replace('/[^A-Z]/', '', strtoupper(@$address['province']));
        $address['postcode'] = preg_replace('/[^A-Z0-9- ]/', '', strtoupper($address['postcode']));
        if (strlen($address['country']) != 2) {
            Log::error('Invalid address for order estimate.');
            return $this->badRequest('Invalid parameters.');
        } elseif ($address['country'] == 'CA' && (strlen($address['province']) != 2 || strlen($address['postcode']) < 6)) {
            Log::error('Invalid province code for order estimate.');
            return $this->badRequest('Shipments to Canada must include a province code.');
        }

        // Prepare API request body.
        $body = [];
        $body['items'] = [];
        $body['shipping_address'] = [];
        $body['shipping_address']['country'] = $address['country'];
        $body['shipping_address']['postcode'] = $address['postcode'];
        if ($address['country'] == 'CA') {
            $body['shipping_address']['province'] = $address['province'];
        }

        // Format product list.
        foreach ($items as $item)
        {
            $std = [];
            $std['id'] = (int) $item['id'];
            $std['quantity'] = isset($item['quantity']) ? (int) $item['quantity'] : 1;
            $body['items'][] = $std;
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
     * Places an order and redirects user to payment page.
     *
     * @param \App\Models\Customer $customer
     * @param array $itemList
     * @param array $shippingDetails
     * @param array $shippingAddress
     * @param array $billingAddress
     * @return mixed
     */
    public function placeOrder(Customer $customer, array $itemList, array $shippingDetails, array $shippingAddress, array $billingAddress = null)
    {
        // Build request body.
        $data = [];

        // Set return URLs.
        $data['return_url'] = route('api.orders.return');

        // Set order details.
        $data['customer'] = $customer->toArray();
        $data['items'] = $itemList;
        $data['shipping'] = $shippingDetails;
        $data['shipping_address'] = $shippingAddress;
        if ($billingAddress) {
            $data['billing_address'] = $billingAddress;
        }

        return KemAPI::post($this->baseRequest, $data);
    }
}
