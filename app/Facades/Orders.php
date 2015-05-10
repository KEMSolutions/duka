<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Orders extends Facade {

    protected static function getFacadeAccessor() { return 'App\ApiObjects\Orders'; }

}
