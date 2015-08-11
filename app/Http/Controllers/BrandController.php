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
        if (Brands::isError($brand))
        {
            // Check that requested brand is not actually a category.
            $cat = Categories::get($slug, $this->getRequestParams());
            if (!Categories::isError($cat)) {
                return redirect(route('category', ['slug' => $cat->slug]));
            }

            abort(404);
        }

        // If we have a category, redirect to proper route.
        if (array_key_exists($brand->name, Categories::getAllCategories())) {
            return redirect(route('category', ['slug' => $brand->slug]));
        }

        // Create a paginator instance.
        $paginator = $this->getPaginator($brand, route('brand', ['slug' => $slug]));

        // Return the view.
        return $this->getView($brand, $paginator);
    }
}

