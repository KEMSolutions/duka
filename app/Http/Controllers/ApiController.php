<?php namespace App\Http\Controllers;

use Log;
use Auth;
use Lang;
use Store;
use Brands;
use Orders;
use Cookie;
use Layouts;
use Request;
use Session;
use Products;
use Redirect;
use Customers;
use Categories;
use Localization;

use App\Models\Customer;
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
    // Addresses related methods.
    //


    /**
     * Gets the addresses of an authenticated user.
     */
    public function getAddresses() {
        return $this->send(Auth::user()->addresses);
    }

    /**
     * Retrieves a specific address.
     */
    public function getAddress($id)
    {
        // Since we will only return the address if it belongs to the authenticated user,
        // we will pull from that list directly instead of requesting /addresses/{id} on
        // the main API.
        $address = null;
        foreach (Auth::user()->addresses as $object)
        {
            if ($object->id == $id)
            {
                $address = $object;
                break;
            }
        }

        return $this->send($address);
    }

    /**
     * Creates an address under the currently authenticated user.
     */
    public function createAddress() {
        return $this->badRequest('Not Implemented.', 501);
    }

    /**
     * Updates an address belonging to the currently authenticated user.
     */
    public function updateAddress() {
        return $this->badRequest('Not Implemented.', 501);
    }


    //
    // Categories related methods.
    //


    /**
     * Retrieves the details for a brand.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getBrand($id = null)
    {
        // If no ID was provided, assume we want all brands.
        if ($id < 1) {
            $result = Brands::all();
        }

        else
        {
            $result = Brands::get($id, [
                'page' => Request::input('page', 1),
                'per_page' => Request::input('per_page', 40),
                'embed' => ['products', 'presentation'],
                'filters' => Request::input('filters'),
                'order' => Request::input('order')
            ]);
        }

        return $this->send($result);
    }

    /**
     * Retrieves the details for a category.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getCategory($id = null)
    {
        // If no ID was provided, assume we want all brands.
        if ($id < 1) {
            $result = Categories::all();
        }

        else
        {
            $result = Categories::get($id, [
                'page' => Request::input('page', 1),
                'per_page' => Request::input('per_page', 40),
                'embed' => ['products', 'presentation'],
                'filters' => Request::input('filters'),
                'order' => Request::input('order')
            ]);
        }

        return $this->send($result);
    }


    //
    // Customers related methods.
    //


    /**
     * Gets a customer object (without the "metadata" field).
     *
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function getCustomer()
    {
        // Retrieve customer details.
        $details = (array) Auth::user();

        // Make sure we only return those fields specified by the main API and that are
        // relevant to the requesting client.
        $keep = ['id', 'email', 'postcode', 'name', 'locale', 'language', 'phone'];

        // Remove unwanted fields.
        return $this->send(array_only($details, $keep));
    }


    //
    // Layouts related methods.
    //

    /**
     * Gets supported locales.
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response|static
     *
     * TODO: use /locales endpoint instead of retrieving locales through store embeds.
     */
    public function getLocales($id = null)
    {
        $locales = Store::locales();

        // If an ID was provided, return that specific locale.
        if ($id) {
            $result = array_key_exists($id, $locales) ? $locales[$id] : null;
        }

        else {
            $result = $locales;
        }

        return $this->send($result);
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
        // Retrieve shipping address.
        $shipAddress = Request::input('shipping_address');
        if (!array_key_exists('name', $shipAddress)) {
            $shipAddress['name'] = $shipAddress['firstname'] .' '. $shipAddress['lastname'];
        }

        // Retrieve billing address.
        $useShipAddress = Request::input('use_shipping_address', false);
        if ($useShipAddress) {
            $billAddress = null;
        } else {
            $billAddress = Request::input('billing_address');
            if (!array_key_exists('name', $billAddress)) {
                $billAddress['name'] = $billAddress['firstname'] .' '. $billAddress['lastname'];
            }
        }

        // Retrieve customer details.
        $customer = Auth::guest() ?
            new Customer(['email' => Request::input('email')]) :
            new Customer(['id' => Auth::user()->id]);


        $customer->marketing_optin = Request::input("marketing_email_optin") ? true : false;

        // Retrieve other details.
        $itemList = Request::input('products');
        $shippingDetails = json_decode(utf8_encode(base64_decode(Request::input('shipping'))), true);

        // Place order.
        $response = Orders::placeOrder($customer, $itemList, $shippingDetails, $shipAddress, $billAddress);

        // If we have errors, redirect to cart and display an error message.
        if (Orders::isError($response))
        {
            $redirect = route('cart');
            Log::error($response->error);
            Session::push('messages', Lang::get('boukem.error_occurred'));
        }

        else
        {
            $redirect = $response->payment_details->payment_url;

            // If the customer hasn't created their password yet, we'll have to ask them
            // to create one later.
            $user = new Customer((array) $response->customer);
            if (!isset($user->metadata['password'])) {
                Cookie::queue('unregistered_user', $user->id, 2628000);
            }

            // Save the user ID in the session so we can retrieve their order details
            // when they come back from the payment page.
            Session::put('latest_order_user_id', $user->id);
        }

        return Request::ajax() ? $this->send($response) : Redirect::to($redirect);
    }

    public function getOrderDetails($id, $verification)
    {
        // Retrieve order details.
        $order = Orders::details($id, $verification, Session::pull('latest_order_user_id', null));

        return Request::ajax() ? $this->send($order) : (array) $order;
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
    // Helper methods.
    //


    protected function send($data) {
        return JsonResponse::create($data, 200);
    }

    public function badRequest($msg = 'Bad Request.', $status = 400) {
        return JsonResponse::create(['status' => $status, 'error' => $msg], $status);
    }
}
