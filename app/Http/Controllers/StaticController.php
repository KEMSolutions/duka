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
        $image = \Cache::remember('app_http_controllers_staticcontroller_favicon_' . $size, 120, function() use ($size) {
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


    /**
     * Return the main application stylesheet with the dynamic site colors applied to it.
     *
     * @return Response
     */
    public function getMainStylesheet(){

        /* 
        We have to seriously hack things up here. 
        First, we'll load the production stylesheet.
        We'll then search and replace the 5 reserved application colors with the ones we received from the api.
        When cache all that and return it to the browser, as if it was loading a static stylesheet.
        You have a better idea? Feel free to improve this code :)
        */
        
        $style = \Cache::remember('app_http_controllers_staticcontroller_stylesheet', 0, function() {
            
            $sheetPath = public_path() . '/css/prod/app.css';
            $sheet = file_get_contents($sheetPath,"r");

            $color_one = \Store::info()->colors->color_one;
            $color_two = \Store::info()->colors->color_two;
            $color_three = \Store::info()->colors->color_three;
            $color_four = \Store::info()->colors->color_four;
            $color_five = \Store::info()->colors->color_five;

            $color_one_dark = \Utilities::adjustBrightness($color_one, -50);
            $color_two_dark = \Utilities::adjustBrightness($color_two, -50);
            $color_three_dark = \Utilities::adjustBrightness($color_three, -50);
            $color_four_dark = \Utilities::adjustBrightness($color_four, -50);
            $color_five_dark = \Utilities::adjustBrightness($color_five, -50);

            $color_one_light = \Utilities::adjustBrightness($color_one, +50);
            $color_two_light = \Utilities::adjustBrightness($color_two, +50);
            $color_three_light = \Utilities::adjustBrightness($color_three, +50);
            $color_four_light = \Utilities::adjustBrightness($color_four, +50);
            $color_five_light = \Utilities::adjustBrightness($color_five, +50);

//          Replace the placeholder colors with the actual ones from the API
            $sheet = str_replace('#00F0F0', '#' . $color_one, $sheet);
            $sheet = str_replace('#00E0E0', '#' . $color_one_light, $sheet);
            $sheet = str_replace('#11F1F1', '#' . $color_one_dark, $sheet);

            $sheet = str_replace('#0FFF00', '#' . $color_two, $sheet);
            $sheet = str_replace('#0EEE00', '#' . $color_two_light, $sheet);
            $sheet = str_replace('#1FFF11', '#' . $color_two_dark, $sheet);

            $sheet = str_replace('#F000FF', '#' . $color_three, $sheet);
            $sheet = str_replace('#E000EE', '#' . $color_three_light, $sheet);
            $sheet = str_replace('#F111FF', '#' . $color_three_dark, $sheet);

            $sheet = str_replace('#000FFF', '#' . $color_four, $sheet);
            $sheet = str_replace('#000EEE', '#' . $color_four_light, $sheet);
            $sheet = str_replace('#111FFF', '#' . $color_four_dark, $sheet);

            $sheet = str_replace('#F0FFF0', '#' . $color_five, $sheet);
            $sheet = str_replace('#E0EEE0', '#' . $color_five_light, $sheet);
            $sheet = str_replace('#F1FFF1', '#' . $color_five_dark, $sheet);

            return $sheet;
        });

        return response($style)->header('Content-Type', "text/css");

    }


}
