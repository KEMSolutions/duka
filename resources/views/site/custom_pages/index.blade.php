@extends("app")

    @section("content")
        <div class="ui container">
            <h1>{{ $title }}</h1>
            {!! $html !!}
        </div>
    @endsection

