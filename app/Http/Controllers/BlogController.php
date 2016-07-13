<?php namespace App\Http\Controllers;

use Blogs;
use Lang;
use Store;
use Localization;
use URL;
use App;

use Illuminate\Support\Arr;
use cebe\markdown\MarkdownExtra;

class BlogController extends Controller
{
    function cleanString($text) {
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
            '/[“”«»„]/u'    =>   ' ', // Double quote
            '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFeed()
    {


        // create new feed
        $feed = \App::make("feed");


        // cache the feed for 60 minutes (second parameter is optional)
        $feed->setCache(60, intval(\KemAPI::getUser()) . 'app_http_controllers_blogcontroller_feed' . App::getLocale());

        // check if there is cached feed and build new only if is not
        if (!$feed->isCached())
        {

            $blogs = Blogs::all();


           // set your feed's title, description, link, pubdate and language
           $feed->title = Store::info()->name . " - " . Lang::get("boukem.blog");
           $feed->icon = url('/favicon.png');
           $feed->description = null;
           $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
           if (count($blogs) > 0){
            $feed->pubdate = $blogs[0]->date;
           }
           $feed->lang = Localization::getCurrentLocale();
           $feed->setShortening(false);

           foreach ($blogs as $blog)
           {
               // set item's title, author, url, pubdate, description and content
               $authorName = $this->cleanString(urldecode($blog->author->name));
               $feed->add($blog->title, htmlspecialchars(strip_tags($authorName), ENT_COMPAT, 'UTF-8'), URL::action('BlogController@show', ["slug"=>$blog->slug]), $blog->date, $blog->lead, $this->parser->parse($blog->content));
           }

        }

        // first param is the feed format
        // optional: second param is cache duration (value of 0 turns off caching)
        // optional: you can set custom cache key with 3rd param as string
        return $feed->render('atom');


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $blogs = Blogs::all();
        if (count($blogs) == 0){
            abort(404, Lang::get('boukem.error_occurred'));
        }

        return view('site.blog.index', [
            'blogs' => $blogs,
        ]);

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        // Retrieve page content.
        $blog = Blogs::get($slug);
        if (Blogs::isError($blog)) {
            abort(404, Lang::get('boukem.error_occurred'));
        }

        $html = $this->parser->parse($blog->content);
        return view('site.blog.view', [
            'blog' => $blog,
            'html' => $html
        ]);

    }


}
