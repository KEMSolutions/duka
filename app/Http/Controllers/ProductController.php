<?php namespace App\Http\Controllers;

use App\Facades\KemAPI;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProductController extends Controller {

    public function show($id){
        return View::make("product.view")->with([
           "product" => KemAPI::getProduct($id),
            "locale" => LaravelLocalization::getCurrentLocale()
        ]);
    }

}
