<?php namespace App\Http\Controllers;

use View;
use Request;
use Redirect;
use Products;
use Utilities;
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
        $product = Products::get($id);
        $locale = Localization::getCurrentLocale();
        
        if (!isset($product->localization) || $product->localization->locale->language != $locale){
            abort(404);
        }

        // Build a list of alternatives (if any)
        $alternatives = [];
        foreach ($product->localization->alt as $localeid => $alt) {

            $alternative = new \stdClass;
            $alternative->locale = $alt->locale;
            $alternative->url = Localization::getLocalizedURL($alt->locale->language, route("product", ["slug"=>$alt->slug]));
            $alternatives[] = $alternative;
        }

        return View::make("product.view")->with([
            "product" => $product,
            "locale" => $locale,
            "alternatives"=>$alternatives,
            "supported_countries" => ["FR", "DE", "US", "GB", "IT", "BE", "CH", "LU", "ES", "PT", "MX"],
            "country_code" => Utilities::getUserCountryCode()
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
            'displayed' => count($results->organic_results),
            'total' => $results->paginationTotal,
            'border' => false
        ]);
    }
}

