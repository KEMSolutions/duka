@extends("...app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
    @endsection

    @section("content")

        {{-- Display search results --}}
        @if (count($results->organic_results))
            @include('site.search._results', ['results' => $results->organic_results])
        @else
            @include('site.search._no-results-found')
        @endif

    @endsection

@stop

