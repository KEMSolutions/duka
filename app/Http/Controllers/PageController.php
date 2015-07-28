<?php namespace App\Http\Controllers;

use Lang;
use Pages;

use cebe\markdown\MarkdownExtra;

/**
 * Class PagesController
 * @package App\Http\Controllers
 */
class PageController extends Controller
{
    /**
     * @var MarkdownExtra   Markdown parser.
     */
    protected $parser;

    /**
     * @param MarkdownExtra $parser
     */
    public function __construct(MarkdownExtra $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param $slug
     * @return \Illuminate\View\View
     */
	public function display($slug)
    {
        // Retrieve page content.
        $page = Pages::get($slug);
        if (empty($page) || (property_exists($page, 'status') && $page->status != 200)) {
            abort(404, Lang::get('boukem.error_occurred'));
        }

        // Convert to HTML.
        switch ($page->type)
        {
            case 'markdown':
                $html = $this->parser->parse($page->content);
                break;

            default:
                $html = $page->content;
        }

        return view('site.pages.index', ['html' => $html]);
    }
}

