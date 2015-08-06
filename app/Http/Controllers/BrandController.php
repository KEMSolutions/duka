<?php namespace App\Http\Controllers;

use View;
use Request;
use Brands;
use Localization;

use App\Http\Requests;

class BrandController extends CategoryController
{
    /**
     * @param $slug
     * @return mixed
     */
    public function display($slug)
    {
        // Retrieve brand details.
        $brand = Brands::get($slug, $this->getRequestParams());

        // Handle errors.
        if (Brands::isError($brand)) {
            abort(404);
        }

        // Create a paginator instance.
        $paginator = $this->getPaginator($brand, route('brand', ['slug' => $slug]));

        // Return the view.
        return $this->getView($brand, $paginator);
    }
}

