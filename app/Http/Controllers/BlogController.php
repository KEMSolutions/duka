<?php namespace App\Http\Controllers;

use Blogs;
use Lang;
use Feed;
use Store;
use Localization;
use URL;

use Illuminate\Support\Arr;
use cebe\markdown\MarkdownExtra;

class BlogController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFeed()
    {
        
        

        // create new feed
        $feed = Feed::make();

        
        // cache the feed for 60 minutes (second parameter is optional)
        $feed->setCache(60, intval(\KemAPI::getUser()) . 'app_http_controllers_blogcontroller_feed');

        // check if there is cached feed and build new only if is not
        if (!$feed->isCached())
        {

            $blogs = Blogs::all();
            if (count($blogs) == 0){
                abort(404, Lang::get('boukem.error_occurred'));
            }

           // set your feed's title, description, link, pubdate and language
           $feed->title = Store::info()->name . " - " . Lang::get("boukem.blog");
           $feed->icon = url('/favicon.png');
           $feed->description = null;
           $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
           $feed->pubdate = $blogs[0]->date;
           $feed->lang = Localization::getCurrentLocale();
           $feed->setShortening(false);
           
           foreach ($blogs as $blog)
           {
               // set item's title, author, url, pubdate, description and content
               $feed->add($blog->title, $blog->author->name, URL::action('BlogController@show', ["slug"=>$blog->slug]), $blog->date, $blog->lead, $this->parser->parse($blog->content));
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
