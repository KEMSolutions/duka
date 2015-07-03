<?php namespace App\ApiObjects;

class Categories extends KemApiObject
{
    public function __construct() { parent::__construct('categories'); }

    /**
     * Retrieves a nested list of categories.
     *
     * @return array    List of categories.
     */
    public function getAllCategories() {
        return parent::all()[0]->children;
    }

    /**
     * Retrieves a nested list of conditions (sujets de santé).
     *
     * @return array    List of conditions.
     */
    public function getAllConditions() {
        return parent::all()[1]->children;
    }

    /**
     * Retrieves the details for a category.
     *
     * @param mixed $id     ID or slug of the category.
     * @param int $page     The page to start from (see: https://developer.github.com/v3/#pagination).
     * @param int $perPage  The number of products to display per page (see: https://developer.github.com/v3/#pagination).
     * @return object       Brand object.
     */
    public function get($id, $page = 1, $perPage = 40) {
        return parent::get($id, [
            'embed' => 'products,presentation',
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

}
