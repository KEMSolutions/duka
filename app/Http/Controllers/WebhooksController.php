<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WebhooksController extends Controller
{

    /**
     * Receive incoming webhooks from the console, mainly to invalidate the cache.
     *
     * @return Response
     */
    public function postReceive(Request $request)
    {
        
        // Temporary flush the whole cache
        \Cache::flush();

    }
}
