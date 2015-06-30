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

        dd($category);
    }
}


