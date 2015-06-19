<?php namespace App\Http\Controllers;

use View;
use Request;
use Products;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index()
    {
        // Retrieve search query and other details.
        $query = trim(Request::input('q', ''));
        $page = (int) Request::input('page', 1);
        $perPage = (int) Request::input('per_page', 40);

        // Retrieve search results.
        $results = strlen($query) ? Products::search($query, $page, $perPage) : null;

        return View::make('site.search.index', [
            'query' => $query,
            'page' => $page,
            'perPage' => $perPage,
            'results' => $results
        ]);
    }
}
