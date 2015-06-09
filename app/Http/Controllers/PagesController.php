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
        $content = Pages::get($slug);
        if (empty($content) || (property_exists($content, 'status') && $content->status != 200)) {
            abort(404, Lang::get('boukem.error_occurred'));
        }

        // Convert to HTML.
        switch ($content->type)
        {
            case 'markdown':
                $html = '';
                break;

            default:
                $html = $content;
        }

        dd($html);
    }

}
