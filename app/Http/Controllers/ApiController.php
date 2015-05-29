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

    public function getBrand($id) {
        return $this->send(Brands::get($id));
    }

    public function getCategory($id) {
        return $this->send(Categories::get($id));
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
