<?php namespace App\Http\Controllers;

use Brands;
use Categories;
use Illuminate\Support\Collection;
use Layouts;
use Products;
use Request;
use Orders;
use App\Http\Controllers\Controller;


class ApiController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function getBrand($id)
    {
        return Collection::make(Brands::get($id));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCategory($id)
    {
        return Collection::make(Categories::get($id));
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getLayout($id = '')
    {
        return Collection::make(Layouts::get($id));
    }

    /**
     * @param $id
     * @return static
     */
    public function getProduct($id)
    {
        return Collection::make(Products::get($id));
    }

    /**
     * @param $query
     * @return static
     */
    public function searchProducts($query)
    {
        return Collection::make(Products::search($query));
    }

    public function getOrderEstimate()
    {
        $country = Request::input('country');
        $postalCode = Request::input('postcode');
        $products = (array) Request::input('products');

        return Collection::make(Orders::estimate($products, $country, $postalCode));
    }
}
