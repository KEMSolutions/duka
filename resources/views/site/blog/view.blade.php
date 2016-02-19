@extends("app")
@section('title')
{{ $blog->title }}
@endsection


@section("content")
<div class="ui container grid vertically padded">
    <div class="one column row">
        <div class="right floated column">
            <div class="ui breadcrumb pull-right">
            	<a class="section" href="{{ route('home') }}">@lang('boukem.home')</a>
            	<i class="right chevron icon divider"></i>
            	<a class="section" href="{{ action('BlogController@index') }}">@lang('boukem.blog')</a>
				<i class="right chevron icon divider"></i>
                <a class="active section">{{ $blog->title }}</a>
            </div>
        </div>
    </div>
</div>


    <section class="ui main text container">

	    <article  itemscope itemtype="http://schema.org/BlogPosting">
	    <meta itemprop="datePublished" content="{{ $blog->date }}"/>
    		<header>
				<h1 itemprop="name headline" class="ui header">
					{{ $blog->title }}
				</h1>
				<strong itemprop="description">
					{{ $blog->lead }}
				</strong>
		    </header>

		    <div itemprop="articleBody">
        	{!! $html !!}
        	</div>
        </article>
    </section>
@endsection