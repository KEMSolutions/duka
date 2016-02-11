<?php

namespace app\Helpers;

use cebe\markdown\Markdown;
use App\Helpers\Contracts\DukamlContract;

class Dukaml implements DukamlContract
{

	/**
     * @var Markdown   Markdown parser.
     */
    protected $parser;

    /**
     * @param Markdown $parser
     */
    public function __construct()
    {
    	$parser = new \cebe\markdown\Markdown();
        $this->parser = $parser;
    }

   /**
     * Takes a raw dukaml string and converts it to a PHP array.
     *
     * @param str 		$dukaml           A raw dukaml object
     * @return SimpleXMLElement	the native PHP array.
     */
	protected function unserializeRawDukaml($dukaml){

		$document = new \SimpleXMLElement($dukaml);
		return $document;

	}

	protected $runs = 0;
	/**
     * Converts dukaml content to HTML.
     * Dukaml content is, at this time, typically Markdown. However, always use this function as specifications might
     * change at some point, especially regarding photos.
     *
     * @return array   Array of all defined objects in this scope.
     */
	public function renderContentToHtml($content) {
		$this->parser->html5 = true;
		$converted = $this->parser->parse($content);

		return $converted;
	}

	protected function widthForColumn($column){


		$columnAttributes = $column->attributes();

		$witdh = isset($columnAttributes->width) ? intval($columnAttributes->width) : 1;
		
		if ($witdh > 0) {
			return $witdh;
		}

		return 1;
	}

	/**
     * 
     * Dukaml columns width is determined in proportion (eg. column width=1, another column with=2) of the row.
     * This way, we avoid making css framework or even web biased assumptions (eg. are we using Bootstrap or Semantic UI 
     * on the front-end? Or are we dealing with an iOS's UICollectionView?). This function takes a column and finds this
     * ratio based on the parent row's columns total width.
     *
     * @return array   Array of all defined objects in this scope.
     */
	public function relativeWidthForColumn($column) {
		
		$row = $column->xpath("..")[0];

		$totalWidth = 0;
		foreach ($row->column as $individialcolumn){
			$totalWidth += $this->widthForColumn($individialcolumn);
		}

		$relativeWidth = $this->widthForColumn($column) / $totalWidth;

		return $relativeWidth;
	}

	/**
     * 
     * Apply a column's relative width ratio to a specific grid system (eg. Semantic UI's 16 or Bootstrap's 12)
     *
     * @return int column width in grid units
     */
	public function relativeWidthForColumnInGrid($column, $grid=16) {
		
		$relativeWidth = $this->relativeWidthForColumn($column);

		// We cheat a little by rounding to allow bootstrap to semantic compatibility
		// (an 8-4 two column layout is not possible on a 16 wide grid)
		return round($grid * $relativeWidth);
	}

	/**
     * 
     * Return the relative width in grid as an english spelled word, for Semantic-Ui compatibility
     *
     * @return float grid relative width
     */
	public function spelledOutRelativeWidthForColumnInGrid($column, $grid=16) {
		$this->runs += 1;
		$relativeWidth = $this->relativeWidthForColumnInGrid($column, $grid);

		/*
		# Ideally, we should do something like that:
		
		$formatter = new \NumberFormatter("en", NumberFormatter::SPELLOUT);
		return $formatter->format($relativeWidth);

		# Unfortunately, php-intl does not come standard in Laravel Homestead nor Forge.
		# To make deployment easier, we revert to this very very basic table.
		*/
		
		$numbers = [
			1=>"one",
			2=>"two",
			3=>"three",
			4=>"four",
			5=>"five",
			6=>"six",
			7=>"seven",
			8=>"height",
			9=>"nine",
			10=>"ten",
			11=>"eleven",
			12=>"twelve",
			13=>"thirteen",
			14=>"fourteen",
			15=>"fifteen",
			16=>"sixteen",
		];


		return isset($numbers[$relativeWidth]) ? $numbers[$relativeWidth] : "one";
	}

	/**
     * Converts a SimpleXMLElement-like array to a Laravel View.
     *
     * @param array     document
     * @return View   a Laravel view.
     */
	protected function renderView($document){

		return view('site.custom_pages.dukaml', ['document' => $document, 'renderer'=>$this]);

	}

	/**
     * Converts a dukaml string to styled HTML.
     *
     * @return string   Pure HTML
     */
    public function renderToHtml($dukaml)
    {

    	$document = $this->unserializeRawDukaml($dukaml);
    	$view = $this->renderView($document);
    	
        return $view->render();

    }

}