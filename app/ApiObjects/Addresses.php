<?php namespace App\ApiObjects;

use Log;
use Cache;
use Products;
use Carbon\Carbon;

class Addresses extends BaseObject
{
    public function __construct() { parent::__construct('addresses'); }
}

