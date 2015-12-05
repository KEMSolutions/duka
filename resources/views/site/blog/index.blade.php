@extends("app")
@section('title')
@lang("boukem.blog")
@stop


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

<section class="ui padded container">

@foreach ($blogs as $blog)
    <div class="ui items">
      <div class="item">
        {{--<div class="ui small image">
          <img src="/images/wireframe/image.png">
        </div>--}}
        <div class="content">
          <div class="header"><a href="{{ action('BlogController@show', ["slug"=>$blog->slug]) }}">{{ $blog->title }}</a></div>
          <div class="meta">
            <span class="author">{{ $blog->author->name }}</span>
            <span class="date">{{ $blog->date }}</span>
          </div>
          <div class="description">
            <p>{{ $blog->lead }}</p>
          </div>
        </div>
      </div>
@endforeach

</section>


@endsection

@stop
