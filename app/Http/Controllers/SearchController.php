<?php namespace App\Http\Controllers;

use View;
use Request;
use Redirect;
use Products;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index()
    {
        // Performance check.
        $query = trim(Request::input('q', ''));
        if (empty($query)) {
            return Redirect::to(route('home'));
        }

        // Retrieve query details.
        $page = (int) Request::input('page', 1);
        $perPage = (int) Request::input('per_page', 40);

        // Retrieve search results.
        $results = Products::search($query, $page, $perPage);

        // Create a paginator instance.
        $paginator = null;
        if (count($results->organic_results))
        {
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $results->organic_results,
                $results->total,
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
            'paginator' => $paginator
        ]);
    }
}
