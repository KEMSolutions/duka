<?php namespace App\Http\Controllers;

use View;
use Request;
use Redirect;
use Products;
use Localization;

use App\Http\Controllers\Controller;

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

    /**
     * @return mixed
     */
    public function search()
    {
        // Performance check.
        $query = trim(Request::input('q'));
        if (empty($query)) {
            return Redirect::to(route('home'));
        }

        // Retrieve query details.
        $page = (int) Request::input('page', 1);
        $perPage = (int) Request::input('per_page', 40);

        // Retrieve search results.
        $results = Products::search($query, $page, $perPage, ['filters' => Request::input('filters')]);

        // Create a paginator instance.
        $paginator = null;
        if (count($results->organic_results))
        {
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $results->organic_results,
                $results->paginationTotal,
                $perPage,
                $page
            );

            $paginator->setPath(route('search'));
            $paginator->appends(['q' => $query, 'per_page' => $perPage]);
        }

        return View::make('site.search.index', [
            'query' => $query,
            'page' => $page,
            'perPage' => $perPage,
            'results' => $results,
            'paginator' => $paginator,
            'border' => false
        ]);
    }
}

