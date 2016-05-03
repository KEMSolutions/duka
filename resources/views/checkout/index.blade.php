@extends("app")

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

    @section("custom_scripts")
        {{-- We only need one cart-drawer on the checkout page, so we'll recklessly
             delete the cart-drawer from the dom on the checkout page.
         --}}
        <script>
            $(".cart-drawer").remove();
        </script>
    @endsection

