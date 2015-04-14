<?php namespace App\Http\Controllers;

use App\Facades\KemAPI;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LayoutController extends Controller {

    /**
     *Renders the homepage view.
     *
     * @return mixed
     */
    public function init()
    {
        $apiData = KemAPI::getHomePage();
        $currentLocale = LaravelLocalization::getCurrentLocale();
        $elementType = [];
        $layoutData = [];


        //get all the data types presented on the api call
        for($i = 0; $i < count($apiData); $i++)
        {
            array_push($elementType, $apiData[$i]->type);
        }

        //for all the data types, create an array containing its relevant information, according to the current locale.
        for($i = 0; $i < count($elementType); $i++)
        {
            $layoutData[$elementType[$i]] = $this->getData($apiData, $currentLocale, $i);

            if ($elementType[$i] == "headline")
            {
                $layoutData["headline"] = $this->getHeadlineContent($apiData, $currentLocale, $i);
            }
        }

        //return the homepage view.
        //To access data on each section, do {{ $LayoutData["name_of_section"]["property"]  }}
        return View::make("site._homepage")->with([
            "sites" => $elementType,

            "showTab" => true,
            "color" => "color-two",

            "layoutData" => $layoutData
        ]);
    }

    /**
     * Private method to create an array containing generic information.
     * Used for those sections :
     *          -mixed
     *          -rebates
     *          -featured
     *
     * @param $source
     * @param $locale
     * @param $position
     * @return array
     */
    private function getData($source, $locale, $position)
    {
        return [
            "tabTitle" => $locale === "fr" ? $source[$position]->content->tab->title_fr_CA : $source[$position]->content->tab->title_en_CA,
            "products" => isset($source[$position]->content->products) ? $source[$position]->content->products : null
        ];
    }

    /**
     * Private method to create an array containing information about the headline section
     *
     * @param $source
     * @param $locale
     * @param $position
     * @return array
     */
    private function getHeadlineContent($source, $locale, $position){
        return [
            "style" => $source[$position]->content->style,
            "backgroundUrl" => $source[$position]->content->background->url,
            "tabTitle" => $locale === "fr" ? $source[$position]->content->tab->title_fr_CA : $source[$position]->content->tab->title_en_CA,
            "title" => $locale === "fr" ? $source[$position]->content->title_fr_CA : $source[$position]->content->title_en_CA,
            "subtitle" => $locale === "fr" ? $source[$position]->content->subtitle_fr_CA : $source[$position]->content->subtitle_en_CA
        ];
    }
}


