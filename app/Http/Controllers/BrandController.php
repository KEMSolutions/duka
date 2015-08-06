<?php namespace App\Http\Controllers;

use Brands;
use Categories;
use Localization;

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

        // If we have a category, redirect to proper route.
        if (array_key_exists($brand->name, Categories::getAllCategories())) {
            return redirect(route('category', ['slug', $brand->slug]));
        }

        // Create a paginator instance.
        $paginator = $this->getPaginator($brand, route('brand', ['slug' => $slug]));

        // Return the view.
        return $this->getView($brand, $paginator);
    }
}

