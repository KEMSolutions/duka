<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class KemAPI extends Facade {

    protected static function getFacadeAccessor() { return 'kemapihttpclient'; }

}