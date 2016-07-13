<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
    <title type="text">{{ Store::info()->name . " - " . Lang::get("boukem.blog") }}</title>
    <link href="http://duka.local:8000/fr/blog/rss"></link>
    <id>{{ action('BlogController@getFeed') }}</id>
    <language>{{ App::getLocale() }}</language>
    <lastBuildDate>{{ Carbon\Carbon::now() }}</lastBuildDate>
    <atom:link rel="self" type="application/atom+xml" href="{{ action('BlogController@getFeed') }}" />
    <icon>{{ url('/favicon.png') }}</icon>
        @if (count($blogs) > 0)
        <updated>{{ $blogs[0]->date }}</updated>
        @endif
        @foreach ($blogs as $blog)
        <entry>
            <author>
                <name><![CDATA[{!! $blog->author->name !!}]]></name>
            </author>
            @if (isset($blog->image->url))
            <enclosure length="1234" type="image/jpeg" url="{{ Utilities::setImageSizeAndMode(600, 600, 'fit', $blog->image->url) }}" />
            @endif
            <title type="text"><![CDATA[{!! $blog->title !!}}]]></title>
            <link rel="alternate" type="text/html" href="{{ URL::action('BlogController@show', ["slug"=>$blog->slug]) }}"></link>
            <id>{{ URL::action('BlogController@show', ["slug"=>$blog->slug]) }}</id>
            <summary type="html"><![CDATA[{!! $blog->lead !!}]]></summary>
            <content type="html"><![CDATA[{!!$parser->parse($blog->content)!!}]]></content>
            <updated>{{ $blog->date }}</updated>
        </entry>
        @endforeach
    </channel>
</rss>
