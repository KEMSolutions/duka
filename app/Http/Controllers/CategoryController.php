<?php namespace App\Http\Controllers;

use App\Facades\Categories;
use App\Facades\KemAPI;
use App\Http\Requests;

use View;
use Request;
use Localization;

class CategoryController extends Controller {


    public function display($slug)
    {

        // Retrieve category details
        $category = Categories::get($slug);

        // Retrieve query details.
        $page = 1;
        $perPage = 10;

        //Retrieve all products
        $results = $category->products;

        // Create a paginator instance.
        $paginator = null;
        if (count($category->products))
        {
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $results,
                count($results),
                $perPage,
                $page
            );

            $paginator->setPath(route('category', ["slug" => $slug]));
            $paginator->appends(['per_page' => $perPage]);

        }



        return View::make("site.category.index")->with([
            "name" => $category->name,
            "featured" => $category->featured,
            "products" => $category->products,
            "children" => $category->children,
            "presentation" => $category->presentation,
            "paginator" => $paginator
        ]);
    }
}


