
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
 <channel>
  <language>{{ App::getLocale() }}</language>
  <title>{{ Store::info()->name . " - " . Lang::get("boukem.blog") }}</title>
  <description></description>
  <link>{{ url("/") }}</link>
  <lastBuildDate>{{ Carbon\Carbon::now() }}</lastBuildDate>
  <atom:link href="{{ url("/") }}" rel="self" type="application/rss+xml"/>
  @foreach ($blogs as $blog)<item>
   <title><![CDATA[{!! $blog->title !!}]]></title>
   <enclosure length="256354" type="image/jpeg" url="{{ Utilities::setImageSizeAndMode(600, 600, 'fit', $blog->image->url) }}" />
   <link><![CDATA[{{ URL::action('BlogController@show', ["slug"=>$blog->slug]) }}]]></link>
   <guid isPermaLink="true">{{ URL::action('BlogController@show', ["slug"=>$blog->slug]) }}</guid>
   <description><![CDATA[{!! $blog->lead !!}]]></description>
   <pubDate>{{ $blog->date }}</pubDate>
   <author>
        <name><![CDATA[{{ $blog->author->name }}]]></name>
    </author>
  </item>@endforeach
 </channel>
</rss>
