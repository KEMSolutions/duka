<?php namespace App\Http\Controllers;

use App\Facades\Categories;
use App\Facades\KemAPI;
use App\Http\Requests;

use View;
use Localization;

class CategoryController extends Controller {


    public function display($slug)
    {
        $category = Categories::get($slug);

        return View::make("site.category.index")->with([
            "name" => $category->name,
            "featured" => $category->featured,
            "products" => $category->products
        ]);
    }
}


