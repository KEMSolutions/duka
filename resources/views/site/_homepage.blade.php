@extends("app")

@section("custom_css")
    <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
@endsection

@section("content")
    @foreach($sites as $site)
        @include("site._" . $site)
    @endforeach
@stop

@section("scripts")
    <script src="/js/cart-drawer.js"></script>
@endsection