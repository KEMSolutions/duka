<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CheckoutController extends Controller {

    public function index()
    {
        return View::make("cart.index");
    }

}
