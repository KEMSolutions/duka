@extends("app")
@section('title')
    @lang("boukem.blog")
@endsection


@section("content")
    <div class="ui container grid vertically padded">
        <div class="one column row">
            <div class="right floated column">
                <div class="ui breadcrumb pull-right">
                    <a class="section" href="{{ route('home') }}">@lang('boukem.home')</a>
                    <i class="right chevron icon divider"></i>
                    <a class="active section">@lang('boukem.blog')</a>
                </div>
            </div>
        </div>
    </div>

    <section class="ui container">
        <div class="ui padded grid">
            <div class="ui row items">
                @foreach ($blogs as $blog)

                    <div class="item">
                        @if(isset($blog->image->url))
                            <div class="ui small image">
                                <img src="{{ Utilities::setImageSizeAndMode(400, 400, 'fit', $blog->image->url) }}">
                            </div>
                        @endif


                        <div class="content">
                            @if(isset($blog->slug) && isset($blog->title))
                                <div class="header"><a href="{{ action('BlogController@show', ["slug"=>$blog->slug]) }}">{{ $blog->title }}</a></div>
                            @endif

                            <div class="meta">
                                <span class="author">{{ $blog->author->name }}</span>
                                <span class="date">{{ $blog->date }}</span>
                            </div>

                            @if(isset($blog->lead))
                                <div class="description">
                                    <p>{{ $blog->lead }}</p>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>


@endsection
