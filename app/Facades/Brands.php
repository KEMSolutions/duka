<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Brands extends Facade {

    protected static function getFacadeAccessor() { return 'App\ApiObjects\Brands'; }

}
