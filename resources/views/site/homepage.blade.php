@extends("app")

@section("custom_css")
    <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
    <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
@endsection

@section("content")
    @foreach($sites as $site)
        {{-- TODO FRANCIS HERE !!!!!
             Temporary fix : check if the sections rebates, featured, mixed have an array of products.
                If they do, @include them
                If not, skip them.
        --}}
        @if(isset($layoutData[$site]["products"]) || $site === "headline")
            @include("site._" . $site)
        @endif
    @endforeach
@stop

@section("scripts")
    <script src="/js/boukem2-utility.js"></script>
    <script src="/js/cart-drawer.js"></script>

    <script>
        $(".indicator-down:first").hide();
    </script>
@endsection