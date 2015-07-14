<?php namespace App\ApiObjects;

class Store extends BaseObject
{
    public function __construct() { parent::__construct('store'); }

    /**
     * Shortcut for self::get('').
     *
     * @return object   Store details.
     */
    public function info() {
        return parent::get('');
    }
}

