<?php namespace App\Http\Controllers;

use Blogs;
use Lang;

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
