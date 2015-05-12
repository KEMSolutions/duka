@extends("app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
    @endsection

    @section("content")
        @if (!isset($_COOKIE["quantityCart"]) || $_COOKIE["quantityCart"] == 0)
            @include("cart._empty")
        @else
            @include("cart._checkout_form")
    @endsection

    @section("scripts")
        <script src="/js/cart-drawer.js"></script>
    @endsection

@stop

