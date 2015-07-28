<?php namespace App\ApiObjects;

class Brands extends BaseObject
{
    public function __construct() { parent::__construct('brands'); }

    /**
     * Retrieves the details for a brand.
     *
     * @param mixed $id     ID or slug of the brand.
     * @param int $page     The page to start from (see: https://developer.github.com/v3/#pagination).
     * @param int $perPage  The number of products to display per page (see: https://developer.github.com/v3/#pagination).
     * @return object       Brand object.
     */
    public function get($id, $page = 1, $perPage = 40) {
        return parent::get($id, [
            'embed' => 'products',
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Retrieves a nested list of brands.
     *
     * @return array    List of categories.
     */
    public function getAllBrands() {
        return parent::all();
    }

}
