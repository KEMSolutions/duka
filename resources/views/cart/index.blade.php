@extends("app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}"/>
    @endsection

    @section("content")
        @if (!isset($_COOKIE["quantityCart"]) || base64_decode($_COOKIE["quantityCart"]) == "0")
            @include("cart._empty")
        @else
            @include("cart._checkout_form_content")
        @endif
    @endsection

    @section("scripts")
        <script src="/js/cart-drawer.js"></script>
        <script src="/js_assets/chosen.jquery.min.js"></script>
        <script src="/js/boukem2-cart.js"></script>
    @endsection

@stop

