<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Request;
use View;

class WishlistController extends Controller {

    public function index()
    {
        return View::make("site.wishlist.index");
	}

}
