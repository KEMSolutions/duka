@extends("app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
    @endsection

    @section("content")
        {{-- Include header --}}
        @include("site.category._header")

        {{-- Include results --}}
        @include('site.category._results', ['results' => $products])
    @endsection



@endsection