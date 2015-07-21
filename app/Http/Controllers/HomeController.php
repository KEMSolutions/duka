<?php namespace App\Http\Controllers;

use App\Http\Requests;

use View;
use Layouts;
use Categories;
use Localization;

use App\Utilities\Utilities;


class HomeController extends Controller {

	/**
	 *Renders the homepage view.
	 *
	 * @return mixed
	 */
	public function index()
	{
		$apiData = Layouts::get('');
		$currentLocale = Localization::getCurrentLocale();
		$elementType = [];
		$layoutData = [];


		// Get all the data types presented on the api call.
		for($i = 0; $i < count($apiData); $i++)
		{
			array_push($elementType, $apiData[$i]->type);
		}

		// For all the data types, create an array containing its relevant information, according to the current locale.
		for($i = 0; $i < count($elementType); $i++)
		{
			$layoutData[$elementType[$i]] = $this->getData($apiData, $currentLocale, $i);

			if ($elementType[$i] == "headline")
			{
				$layoutData["headline"] = $this->getHeadlineContent($apiData, $currentLocale, $i);
			}
		}

		// Add images of relevant sizes to products.
		$this->extendProductImagesArray($layoutData, $elementType);

		// Return the homepage view.
		// To access data on each section, do {{ $LayoutData["name_of_section"]["property"]  }}
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

	/**
	 * Private method to extend the images array in every product with the following keys:
	 * 		-thumbnail
	 * 		-thumbnail_lg
	 * 		-img_featured
	 * 		-img_featured_lg
	 *
	 * @param $layoutData
	 * @param $elementType
	 */
	private function extendProductImagesArray($layoutData, $elementType)
	{
		// Loop through all the layoutData sections.
		for($i = 0; $i<count($layoutData); $i++)
		{
			// Cache current section (remember that $layoutData takes the current section name as a key)
			$section = $layoutData[$elementType[$i]];

			// Loop through the current section and check if there is a "products" array.
			for($j = 0; $j<count($section); $j++)
			{
				if (array_key_exists("products", $section))
				{
					// Cache the current products array.
					$products = $section["products"];

					// Loop through the products array and add the relevant keys/values.
					for ($k = 0; $k<count($products); $k++)
					{
						//Some products can be null (bug or feature?), check them here.
						if ($products[$k] != null)
						{
							$products[$k]->images[0]->thumbnail_lg = Utilities::setImageSizeAndMode(70, 110, "fit", $products[$k]->images[0]->url);
							$products[$k]->images[0]->thumbnail = Utilities::setImageSizeAndMode(60, 60, "fit", $products[$k]->images[0]->url);
							$products[$k]->images[0]->img_featured = Utilities::setImageSizeAndMode(80, 120, "fit", $products[$k]->images[0]->url);
							$products[$k]->images[0]->img_featured_lg = Utilities::setImageSizeAndMode(160, 160, "", $products[$k]->images[0]->url);
						}
					}
				}
			}
		}
	}
}
