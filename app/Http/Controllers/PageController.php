<?php namespace App\Http\Controllers;

use Lang;
use Pages;
use Store;

use Illuminate\Support\Arr;
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
     * Renders a custom page.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
	public function getPage($slug)
    {
        // Retrieve page content.
        $page = Pages::get($slug);
        if (Pages::isError($page) || !$page->visible) {
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

        return view('site.custom_pages.index', [
            'title' => $page->title,
            'html' => $html
        ]);
    }

    /**
     * Renders a contract page.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function getContract($slug)
    {
        if (!$contract = Arr::get(Store::contracts(), $slug, false)) {
            abort(404, Lang::get('boukem.error_occurred'));
        }

        // Convert contract content to HTML.
        $html = $this->parser->parse($contract->content);

        return view('site.custom_pages.index', [
            'title' => $contract->title,
            'html' => $html
        ]);
    }
}

