@extends("app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/chosen_plugin/chosen.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/chosen_plugin/chosen_custom.css') }}"/>
    @endsection

    @section("content")
        @if (!isset($_COOKIE["quantityCart"]) || base64_decode($_COOKIE["quantityCart"]) == "0")
            @include("checkout._empty")
        @else
            <form method="post" action="{{ route('api.orders')  }}" id="cart_form" class="{{ base64_decode($_COOKIE["quantityCart"]) == "0" ? "hidden" : "" }}" autocomplete="on">
                @include("checkout._checkout_form_content")
            </form>
        @endif
    @endsection

    @section("scripts")
        <script src="/js_assets/chosen.jquery.min.js"></script>

    @endsection

@stop
