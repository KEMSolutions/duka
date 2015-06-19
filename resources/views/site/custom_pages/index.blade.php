@extends("...app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
    @endsection

    @section("content")
        {!! $html !!}
    @endsection

    @section("scripts")
        <script src="/js/boukem2-utility.js"></script>
        <script src="/js/cart-drawer.js"></script>

        <script>
            $(".indicator-down:first").hide();
        </script>
    @endsection

@stop

