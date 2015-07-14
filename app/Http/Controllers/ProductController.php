<?php namespace App\Http\Controllers;

use View;
use Products;
use Localization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Displays a product page.
     *
     * @param mixed $id     The ID or slug of the product to be displayed.
     * @return mixed
     */
    public function display($id)
    {
        return View::make("product.view")->with([
            "product" => Products::get($id),
            "locale" => Localization::getCurrentLocale(),
            "supported_countries" => array("US", "FR", "BE", "IT", "CH", "GB", "IE", "ES", "DE"),
            "country_code" => "US" // TODO: Find the current user's country code!
        ]);
    }
}

