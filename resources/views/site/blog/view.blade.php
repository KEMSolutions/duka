@extends("app")
@section('title')
    {{ $blog->title }}
@endsection

@section('custom_metas')
    {{-- Facebook crawler properties --}}
    @if(isset($blog->slug))
        <meta property="og:url" content="{{ action('BlogController@show', ["slug"=>$blog->slug]) }}" />
    @endif

    <meta property="og:type" content="article" />

    @if(isset($blog->title))
        <meta property="og:title" content="{{ $blog->title }}" />
    @endif

    @if(isset($blog->lead))
        <meta property="og:description" content="{{ $blog->lead }}" />
    @endif

    @if(isset($blog->image->url))
        <meta property="og:image" content="{{ Utilities::setImageSizeAndMode(1200, 630, '', $blog->image->url) }}" />
    @endif

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
                    @if(isset($blog->title))
                        <a class="active section">{{ $blog->title }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <section class="ui main text container">

        <article  itemscope itemtype="http://schema.org/BlogPosting">
            @if($blog->slug)
                <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="{{ action('BlogController@show', ["slug"=>$blog->slug]) }}"/>
            @endif

            <meta itemprop="datePublished" content="{{ $blog->date }}"/>
            <meta itemprop="dateModified" content="{{ $blog->date }}"/>
            <header>
                @if($blog->title)
                    <h1 itemprop="name headline" class="ui header">
                        {{ $blog->title }}
                    </h1>
                @endif

                <aside itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="meta">
                <span itemprop="name" >
                        {{ $blog->author->name }}
                </span>
                </aside>

                <aside itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                    <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                    @if (Store::rectangularLogo())
                            <meta itemprop="url" content="{{ Store::rectangularLogo() }}">
                        @else
                            <meta itemprop="url" content="{{ Store::squareLogo($width = 60, $height = 60, $mode = '', $force_bitmap=true) }}">
                            <meta itemprop="width" content="60">
                            <meta itemprop="height" content="60">
                        @endif
                    </span>
                    <meta itemprop="name" content="{{ Store::info()->name }}">
                </aside>

                    @if($blog->lead)
                        <strong class="description" itemprop="description">
                            {{ $blog->lead }}
                        </strong>
                    @endif

            </header>
            <div class="ui divider"></div>

            @if(isset($blog->image->url))
                <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                    <img src="{{ Utilities::setImageSizeAndMode(640, 480, 'fit', $blog->image->url) }}" class="ui fluid image">
                    <meta itemprop="url" content="{{ Utilities::setImageSizeAndMode(800, 800, 'fit,blowup', $blog->image->url) }}">
                    <meta itemprop="width" content="800">
                    <meta itemprop="height" content="800">
                </div>
            @endif

            <div itemprop="articleBody">
                {!! $html !!}
            </div>
        </article>
    </section>
@endsection