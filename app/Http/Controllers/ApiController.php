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
 * Class ApiController. CSRF token is checked automatically.
 *
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function getBrand($id) {
        return $this->send(Brands::get($id));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCategory($id) {
        return $this->send(Categories::get($id));
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getLayout($id = '') {
        return $this->send(Layouts::get($id));
    }

    /**
     * @param $id
     * @return static
     */
    public function getProduct($id) {
        return $this->send(Products::get($id));
    }

    /**
     * @param $query
     * @return static
     */
    public function searchProducts($query) {
        return $this->send(Products::search($query));
    }

    public function getOrderEstimate()
    {
        return $this->send(Orders::estimate(
            (array) Request::input('products'),
            (array) Request::input('shipping_address')
        ));
    }

    protected function send($data) {
        return JsonResponse::create($data, 200);
    }

    public function badRequest($msg = 'Bad Request.') {
        return JsonResponse::create(['status' => 400, 'error' => $msg], 400);
    }
}
