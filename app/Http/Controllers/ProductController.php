<?php namespace App\Http\Controllers;

use App\Facades\KemAPI;
use App\Facades\Products;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProductController extends Controller {

    public function show($slug){
        return View::make("product.view")->with([
           "product" => Products::get($slug),
            "locale" => LaravelLocalization::getCurrentLocale(),
            "supported_countries" => array("US", "FR", "BE", "IT", "CH", "GB", "IE", "ES", "DE"),
            // TODO: Find the current user's country code!
            "country_code" => "US"
        ]);
    }

}
