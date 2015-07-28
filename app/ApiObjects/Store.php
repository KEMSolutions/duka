<?php namespace App\ApiObjects;

use App\Utilities\Utilities;

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

    public function logo($width = 200, $height = 60, $mode = 'fit')
    {
        return Utilities::setImageSizeAndMode($width, $height, '', $this->info()->logo->url);
    }
}

