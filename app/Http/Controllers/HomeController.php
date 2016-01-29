<?php namespace App\Http\Controllers;

use View;
use Layouts;
use Utilities;
use Categories;
use Localization;

use App\Http\Requests;


class HomeController extends Controller
{
	/**
	 * Renders the homepage view.
	 *
	 * @return mixed
	 */
	public function index()
	{
		$currentLocale = Localization::getCurrentLocale();

		$promoted = \Products::promoted();

		// Return the homepage view.
		return View::make("site.homepage.home")->with([
			"promoted"=>$promoted,
		]);
	}



	/**
	 *Renders a json object with localized strings and api endpoints to be used by client side scripts.
	 *
	 * @return mixed
	 */
	public function localizationsAndEndpoints()
	{
		$Localization = include base_path('resources/lang/'. Localization::getCurrentLocale() .'/boukem.php');
		$ApiEndpoints = [
            'estimate'  => route('api.estimate'),
            'placeOrder'=> route('api.orders'),
            'orders'    => [
                'pay'   => route('api.orders.pay', ['id' => ':id', 'verification' => ':verification']),
                'view'  => route('api.orders.view', ['id' => ':id', 'verification' => ':verification'])
            ]
       ];

		return response()->json(compact("Localization", "ApiEndpoints"));
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
