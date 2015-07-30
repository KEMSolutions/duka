<?php namespace App\Http\Controllers;

use Log;
use Lang;
use Brands;
use Orders;
use Cookie;
use Layouts;
use Request;
use Session;
use Products;
use Redirect;
use Categories;
use Localization;

use App\Models\User;
use Illuminate\Http\JsonResponse;

/**
 * This class handles all API requests through Boukem 2, e.g. jQuery AJAX requests.
 * The CSRF token is checked automatically.
 *
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{


    //
    // Categories related methods.
    //


    /**
     * Retrieves the details for a brand.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getBrand($id)
    {
        return $this->send(Brands::get($id, [
            'page' => Request::input('page', 1),
            'per_page' => Request::input('per_page', 40),
            'embed' => ['products', 'presentation'],
            'filters' => Request::input('filters'),
            'order' => Request::input('order')
        ]));
    }

    /**
     * Retrieves the details for a category.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getCategory($id)
    {
        return $this->send(Categories::get($id, [
            'page' => Request::input('page', 1),
            'per_page' => Request::input('per_page', 40),
            'embed' => ['products', 'presentation'],
            'filters' => Request::input('filters'),
            'order' => Request::input('order')
        ]));
    }


    //
    // Layouts related methods.
    //


    /**
     * @param string $id
     * @return mixed
     */
    public function getLayout($id = '') {
        return $this->send(Layouts::get($id));
    }


    //
    // Orders related methods.
    //


    /**
     * Gets an estimation of shipping costs.
     *
     * @return mixed    Order estimate
     */
    public function getOrderEstimate()
    {
        return $this->send(Orders::estimate(
            (array) Request::input('products'),
            (array) Request::input('shipping_address')
        ));
    }

    /**
     * Places an order.
     *
     * @return mixed    Order placement response or HTTP redirect to payment page.
     */
    public function placeOrder()
    {
        // Retrieve shipment address.
        $shipAddress = Request::input('shipping_address');
        $shipAddress['name'] = $shipAddress['firstname'] .' '. $shipAddress['lastname'];

        // Retrieve billing address.
        $useShipAddress = Request::input('use_shipping_address', false);
        if ($useShipAddress) {
            $billAddress = null;
        } else {
            $billAddress = Request::input('billing_address');
            $billAddress['name'] = $billAddress['firstname'] .' '. $billAddress['lastname'];
        }

        // Retrieve other details.
        $email = Request::input('email');
        $items = Request::input('products');
        $shipping = json_decode(base64_decode(Request::input('shipping')), true);

        // Place order.
        $response = Orders::placeOrder($shipping, $items, $email, $shipAddress, $billAddress);

        // If we have errors, redirect to cart and display an error message.
        if (property_exists($response, 'error'))
        {
            $redirect = route('cart');
            Log::error($response->error);
            Session::push('messages', Lang::get('boukem.error_occurred'));
        }

        else
        {
            $redirect = $response->payment_details->payment_url;

            // If user does not already have an account, create one for them.
            // We'll ask them to create a password later.
            $customer = $response->customer;
            if (!User::find($customer->id))
            {
                User::create([
                    'id' => $customer->id,
                    'name' => $shipAddress['name'],
                    'email' => $email,
                    'language' => Localization::getCurrentLocale()
                ]);

                Cookie::queue('unregistered_user', $customer->id, 2628000);
            }
        }

        return Request::ajax() ? $this->send($response) : Redirect::to($redirect);
    }

    public function getOrderDetails($id, $verification)
    {
        // Retrieve order details.
        $order = Orders::details($id, $verification);

        return Request::ajax() ? $this->send($order) : $order;
    }

    /**
     * Redirects user to payment URL for a given order.
     *
     * @param int $id               Order ID.
     * @param string $verification  Order verification.
     * @return mixed
     */
    public function redirectToPaymentPage($id, $verification)
    {
        // Retrieve order details.
        $order = Orders::details($id, $verification);

        // Redirect to payment URL.
        return redirect($order->payment_details->payment_url);
    }

    /**
     * Handles customers returning from the payment page.
     */
    public function returningFromPayment()
    {
        // Redirect to homepage with a message.
        Session::push('messages', Lang::get('boukem.payment_successful'));
        return redirect(route('home'));
    }


    //
    // Products related methods.
    //


    /**
     * @param $id
     * @return mixed
     */
    public function getProduct($id) {
        return $this->send(Products::get($id));
    }

    /**
     * @param $query
     * @return mixed
     */
    public function searchProducts($query)
    {
        return $this->send(Products::search($query,
            Request::input('page', 1),
            Request::input('perPage', 40)
        ));
    }


    //
    // Other generic methods.
    //


    protected function send($data) {
        return JsonResponse::create($data, 200);
    }

    public function badRequest($msg = 'Bad Request.') {
        return JsonResponse::create(['status' => 400, 'error' => $msg], 400);
    }
}
