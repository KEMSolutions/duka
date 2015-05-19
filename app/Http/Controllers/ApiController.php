<?php namespace App\Http\Controllers;

use Brands;
use Categories;
use Layouts;
use Products;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function getBrand($id)
    {
        return \Illuminate\Support\Collection::make(Brands::get($id));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCategory($id)
    {
        return \Illuminate\Support\Collection::make(Categories::get($id));
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getLayout($id = '')
    {
        return \Illuminate\Support\Collection::make(Layouts::get($id));
    }

    /**
     * @param $id
     * @return static
     */
    public function getProduct($id)
    {
        return \Illuminate\Support\Collection::make(Products::get($id));
    }

    /**
     * @param $query
     * @return static
     */
    public function searchProducts($query)
    {
        return \Illuminate\Support\Collection::make(Products::search($query));
    }

    public function getOrderEstimate()
    {
        $country = Request::input('country');
        $postalCode = Request::input('postcode');
        $products = Request::input('products');

        return \Illuminate\Support\Collection::make(Orders::estimate($products, $country, $postalCode));
    }
}
