@extends("app")

@section("content")
    @foreach($sites as $site)
        @include("site._" . $site)
    @endforeach
@stop