@extends("...app")

    @section("content")
        <div class="container">
            <h1>{{ $title }}</h1>
            {!! $html !!}
        </div>
    @endsection

@stop

