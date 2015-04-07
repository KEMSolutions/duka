<?php namespace App\Http\Controllers;

use App\DataDummy;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * Controller responsible for displaying the cart while shopping and during checkouts.
 *
 * Class CheckoutController
 * @package App\Http\Controllers
 */
class CheckoutController extends Controller {

    /**
     *
     *
     * @return string
     */
    public function draw()
    {
        //($id, $name, $price, $thumbnail, $thumbnail_lg)
        $data = [
            "data1" => new DataDummy(1, "Product 1", "39.95", "http://placehold.it/50x50", "http://placehold.it/120x160"),
            "data2" => new DataDummy(2, "Product 2", "20.10", "http://placehold.it/50x50", "http://placehold.it/120x160")
        ];

        return View::make("checkout.drawer")->with([
            "data" => $data
        ]);
	}

    public function cart()
    {
        return "hi";
    }

}
