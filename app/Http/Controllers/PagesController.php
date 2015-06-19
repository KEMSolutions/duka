<?php namespace App\Http\Controllers;

use Lang;
use Pages;

/**
 * Class PagesController
 * @package App\Http\Controllers
 */
class PagesController extends Controller
{
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
                $parser = new \cebe\markdown\MarkdownExtra;
                $html = $parser->parse($page->content);
                break;

            default:
                $html = $page->content;
        }

        return view('site.pages.index', ['html' => $html]);
    }
}
