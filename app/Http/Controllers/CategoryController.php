<?php namespace App\Http\Controllers;

use View;
use Request;
use Localization;

use App\Facades\Categories;
use App\Http\Requests;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    public function display($slug)
    {
        // Retrieve category details.
        $category = Categories::get($slug);

        // Retrieve query details.
        $page = (int) Request::input('page', 1);
        $perPage = (int) Request::input('per_page', 8);

        // Create a paginator instance.
        $paginator = null;
        if (count($category->products))
        {
            // Make sure we have valid query settings.
            $perPage = max(4, min(40, $perPage));
            $page = max(1, min($page, ceil(count($category->products) / $perPage)));

            // Retrieve the requested products, depending on the query details.
            $results = array_slice($category->products, ($page - 1) * $perPage, $perPage);

            // Setup the paginator.
            $paginator = new LengthAwarePaginator($category->products, count($category->products), $perPage, $page);
            $paginator->setPath(route('category', ["slug" => $slug]));
            $paginator->appends(['per_page' => $perPage]);

        }

        return View::make("site.category.index")->with([
            "name" => $category->name,
            "featured" => $category->featured,
            "products" => $results,
            "children" => $category->children,
            "presentation" => $category->presentation,
            "background" => $this->sanitizeBackground($category->presentation->background->image, "1500", "200"),
            "paginator" => $paginator
        ]);
    }

    /**
     * Utility function to replace {width} and {height} placeholders with actual values.
     *
     * @param $background
     * @param $width
     * @param $height
     * @return mixed
     */
    private function sanitizeBackground($background, $width, $height)
    {
        $sanitizedBackground = $background;

        $sanitizedBackground = str_replace("{width}", $width, $sanitizedBackground);
        $sanitizedBackground = str_replace("{height}", $height, $sanitizedBackground);

        return $sanitizedBackground;
    }

}


