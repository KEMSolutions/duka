<?php namespace App\ApiObjects;

class Brands extends BaseObject
{
    public function __construct() { parent::__construct('brands'); }

    /**
     * Retrieves a nested list of brands.
     *
     * @return array    List of categories.
     */
    public function getAllBrands() {
        return $this->sortBy('name', parent::all());
    }
}

