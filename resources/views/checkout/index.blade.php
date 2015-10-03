@extends("app")

    @section("custom_css")
        <link rel="stylesheet" href="{{ asset('/css/chosen_plugin/chosen.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('/css/chosen_plugin/chosen_custom.css') }}"/>
    @endsection

    @section("content")
        @if (!isset($_COOKIE["quantityCart"]) || base64_decode($_COOKIE["quantityCart"]) == "0")
            @include("checkout._empty")
        @else
            <form method="post" action="{{ route('api.orders')  }}" id="cart_form" class="{{ base64_decode($_COOKIE["quantityCart"]) == "0" ? "hidden" : "" }} ui form form-checkout" autocomplete="on">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                @include("checkout._checkout_form")
            </form>
        @endif
    @endsection

@stop

