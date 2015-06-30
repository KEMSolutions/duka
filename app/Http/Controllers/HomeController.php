<?php namespace App\Http\Controllers;

use App\Facades\Categories;
use App\Facades\KemAPI;
use App\Http\Requests;

use View;
use Localization;

class HomeController extends Controller {

	/**
	 *Renders the homepage view.
	 *
	 * @return mixed
	 */
	public function index()
	{
		$apiData = KemAPI::getHomePage();
		$currentLocale = Localization::getCurrentLocale();
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
		return View::make("site.homepage.home")->with([
			"sites" => $elementType,

			"showTab" => true,
			"color" => "color-two",
			"locale" => $currentLocale,

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
			"products" => isset($source[$position]->content->products) ? $source[$position]->content->products : null,
			"layoutDense" => isset($source[$position]->content->dense) ? $source[$position]->content->dense : false,
			"limit" => isset($source[$position]->content->limit) ? $source[$position]->content->limit : null,
			"product_limit" => isset($source[$position]->content->product_limit) ? $source[$position]->content->product_limit : null,
			"category_limit" => isset($source[$position]->content->category_limit) ? $source[$position]->content->category_limit : null,
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
