<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Store extends Facade
{
    protected static function getFacadeAccessor() { return 'App\ApiObjects\Store'; }
}
