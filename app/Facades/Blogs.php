<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Blogs extends Facade {

    protected static function getFacadeAccessor() { return 'App\ApiObjects\Blogs'; }

}
