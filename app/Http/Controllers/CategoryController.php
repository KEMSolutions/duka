<?php namespace App\Http\Controllers;

use Brands;
use Request;
use Categories;
use Localization;

use App\Http\Requests;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    /**
     * Holds the API request parameters.
     *
     * @var array
     */
    protected $requestParams;

    /**
     * @param $slug
     * @return mixed
     */
    public function display($slug)
    {
        // Retrieve category details.
        $category = Categories::get($slug, $this->getRequestParams());

        // Handle errors.
        if (Categories::isError($category))
        {
            // Check to see if category is not actually a brand.
            $brand = Brands::get($slug, $this->getRequestParams());
            if (!Brands::isError($brand)) {
                return redirect(route('brand', ['slug' => $brand->slug]));
            }

            abort(404);
        }

        // If we have a brand, redirect to proper route.
        if (array_key_exists($category->name, Brands::getAllBrands())) {
            return redirect(route('brand', ['slug' => $category->slug]));
        }

        // Create a paginator instance.
        $paginator = $this->getPaginator($category, route('category', ['slug' => $slug]));

        // Return the view.
        return $this->getView($category, $paginator, false);
    }

    protected function getRequestParams()
    {
        // Performance check.
        if (is_array($this->requestParams)) {
            return $this->requestParams;
        }

        // Initialize API request parameters.
        $this->requestParams = [];
        $this->requestParams['embed'] = ['products', 'presentation'];

        // Retrieve query details.
        $this->requestParams['page'] = (int) Request::input('page', 1);
        $this->requestParams['per_page'] = (int) Request::input('per_page', 8);
        $this->requestParams['order'] = Request::input('order', null);

        // Retrieve query filters.
        $filters = [];
        if ($minPrice = (int) Request::input('min_price')) {
            $filters[] = 'min_price:'. $minPrice;
        }
        if ($maxPrice = (int) Request::input('max_price')) {
            $filters[] = 'max_price:'. $maxPrice;
        }
        if ($brands = Request::input('brands')) {
            $filters[] = 'brands:'. $brands;
        }
        if (count($filters)) {
            $this->requestParams['filters'] = implode(',', $filters);
        }

        return $this->requestParams;
    }

    /**
     * Creates a paginator instance.
     *
     * @param $object       A brand or category.
     * @return LengthAwarePaginator|null
     */
    protected function getPaginator($object, $path)
    {
        $paginator = null;

        if ($object->paginationTotal > 1)
        {
            $page = $this->getRequestParams()['page'];
            $perPage = $this->getRequestParams()['per_page'];

            // Make sure we have valid query settings.
            $perPage = max(4, min(40, $perPage));
            $page = max(1, min($page, ceil($object->paginationTotal / $perPage)));

            // Setup the paginator.
            $paginator = new LengthAwarePaginator($object->products, $object->paginationTotal, $perPage, $page);
            $paginator->appends(['per_page' => $perPage]);
            $paginator->setPath($path);
        }

        return $paginator;
    }

    protected function getView($object, $paginator, $isBrand = true)
    {
        return view('site.category.index', [
            'background'    => isset($object->presentation->background->image) ? $this->sanitizeBackground($object->presentation->background->image, '1500', '200') : "",
            'children'      => $object->children,
            'featured'      => isset($object->featured) ? $object->featured : null,
            'locale'        => Localization::getCurrentLocale(),
            'name'          => $object->name,
            'paginator'     => $paginator,
            'presentation'  => $object->presentation,
            'products'      => $object->products,
            'total'         => $object->paginationTotal,
            'isBrand'       => $isBrand
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
        return str_replace(["{width}", "{height}"], [$width, $height], $background);
    }
}

