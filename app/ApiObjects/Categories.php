<?php namespace App\ApiObjects;

class Categories extends BaseObject
{
    public function __construct() { parent::__construct('categories'); }

    /**
     * Retrieves a nested list of categories.
     *
     * @return array    List of categories.
     */
    public function getAllCategories() {

        if (!isset(parent::all()[0])) {
            return [];
        }
        return parent::all()[0]->children;
    }

    /**
     * Retrieves a nested list of conditions (sujets de santé).
     *
     * @return array    List of conditions.
     */
    public function getAllConditions() {
         if (!isset(parent::all()[1])) {
            return [];
        }
        return parent::all()[1]->children;
    }
}

