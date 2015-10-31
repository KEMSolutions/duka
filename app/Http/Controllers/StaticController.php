<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StaticController extends Controller
{

    /**
     * Return a pseudo static favicon image of a given size.
     *
     * @param int requested image size (height and width)
     * @return Response
     */
    public function getFavicon($size=64)
    {
        $image = \Cache::remember('app_http_controllers_staticcontroller_favicon_' . $size, 60, function() use ($size) {
            $imagePath = \Store::logo($width = $size, $height = $size, $mode = 'fit');
            return file_get_contents($imagePath,"r");
        });
        return response($image)->header('Content-Type', "image/png");
    }


    /**
     * Return a pseudo static touch icon
     *
     * @return Response
     */
    public function getTouchIcon($size=64)
    {
        return $this->getFavicon($size=512);
    }


}
