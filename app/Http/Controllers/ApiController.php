<?php namespace App\Http\Controllers;

// Import facades & other dependencies.
use Brands;
use Categories;
use Layouts;
use Orders;
use Products;
use Request;

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
    // Categories
    //

    public function getBrand($id)
    {
        return $this->send(Brands::get($id,
            Request::input('page', 1),
            Request::input('perPage', 40)
        ));
    }

    public function getCategory($id)
    {
        return $this->send(Categories::get($id,
            Request::input('page', 1),
            Request::input('perPage', 40)
        ));
    }

    //
    // Layouts
    //

    public function getLayout($id = '') {
        return $this->send(Layouts::get($id));
    }

    //
    // Orders
    //

    public function getOrderEstimate()
    {
        return $this->send(Orders::estimate(
            (array) Request::input('products'),
            (array) Request::input('shipping_address')
        ));
    }

    public function placeOrder()
    {
        $shipAddress = Request::input('shipping_address');
        $shipAddress = [
            "postcode"=>"H2V 4G7",
            "country"=>"CA",
            "province"=>"QC",
            "line1"=>"5412 avenue du Parc",
            "name"=>"Remy Vanherweghem",
            "city"=>"Montreal",
            "phone"=>"514-441-5488"
        ];
        $billAddress = Request::input('billing_address');
        $shipping = [
            'method' => 'DOM.ZZ',
            'name' => 'Colis accélérés',
            'signature' => '1432685850:l0esPFkm0VyT:434d400b84f7e350423fded032c32029b4a3bbca76a859de25f915ff42db5a5c',
            "price" => "7.86",
            "delivery" => "2015-05-27",
            "transit" => 1,
            "taxes"=> [
                [
                    "rate"=> 9.975,
                    "name"=> "TVQ",
                    "amount"=> 0.78
                ],
                [
                    "rate"=> 5,
                    "name"=> "TPS",
                    "amount"=> 0.39
                ]
            ]
        ];
        $products = [
            [
                "id" => 4321,
                "quantity" => 1
            ],
            [
                "id" => 1234,
                "quantity"=> 2
            ]
        ];
        $email = 'remyv@kemsolutions.com';


        $redirect = Orders::placeOrder($shipping, $products, $shipAddress, $email);

        return \Redirect::to($redirect);
    }

    public function handleSuccessfulPayment()
    {
        dd('Payment successful.');
    }

    public function handleFailedPayment()
    {
        dd('Payment failed.');
    }

    public function handleCancelledPayment()
    {
        dd('Payment cancelled');
    }

    //
    // Products
    //

    public function getProduct($id) {
        return $this->send(Products::get($id));
    }

    public function searchProducts($query)
    {
        return $this->send(Products::search($query,
            Request::input('page', 1),
            Request::input('perPage', 40)
        ));
    }

    //
    // Helper methods
    //

    protected function send($data) {
        return JsonResponse::create($data, 200);
    }

    public function badRequest($msg = 'Bad Request.') {
        return JsonResponse::create(['status' => 400, 'error' => $msg], 400);
    }
}
