<?php namespace App\Http\Controllers;

use View;
use Request;
use Categories;
use Localization;

use App\Http\Requests;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    public function display($slug)
    {
        // Retrieve query details.
        $page = (int) Request::input('page', 1);
        $perPage = (int) Request::input('per_page', 8);

        // Retrieve category details.
        $category = Categories::get($slug, [
            'page' => $page,
            'per_page' => $perPage,
            'embed' => ['products', 'presentation'],
            'filters' => Request::input('filters'),
            'order' => Request::input('order')
        ]);

        // Handle errors.
        if (Categories::isError($category)) {
            abort(404);
        }

        // Create a paginator instance.
        $paginator = null;
        if ($category->paginationTotal > 1)
        {
            // Make sure we have valid query settings.
            $perPage = max(4, min(40, $perPage));
            $page = max(1, min($page, ceil($category->paginationTotal / $perPage)));

            // Retrieve the requested products, depending on the query details.
            //$results = array_slice($category->products, ($page - 1) * $perPage, $perPage);
            $results = $category->products;

            // Setup the paginator.
            $paginator = new LengthAwarePaginator($category->products, $category->paginationTotal, $perPage, $page);
            $paginator->setPath(route('category', ['slug' => $slug]));
            $paginator->appends(['per_page' => $perPage]);
        }

        return View::make("site.category.index")->with([
            "name" => $category->name,
            "featured" => isset($category->featured) ? $category->featured : null,
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


