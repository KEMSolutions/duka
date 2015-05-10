<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Categories extends Facade {

    protected static function getFacadeAccessor() { return 'App\ApiObjects\Categories'; }

}
