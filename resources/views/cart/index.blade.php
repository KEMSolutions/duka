@extends("app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
    @endsection

    @section("content")
        {{-- If cart is empty --}}
        @include("cart._empty")
        {{-- else include the cart--}}

    @endsection

    @section("scripts")
        <script src="/js/cart-drawer.js"></script>
    @endsection

@stop

