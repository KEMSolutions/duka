@extends("app")

@section("custom_css")
    <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
@endsection


@section("content")

    <h1>Hi!</h1>
    <ul>
        @foreach($data as $dummy)
            <li>
                <h2>{{$dummy->getId()}}</h2>
                <h5>{{$dummy->getName()}}</h5>
                <button class="buybutton" data-product="{{$dummy->getId()}}"
                        data-price="{{$dummy->getPrice()}}"
                        data-thumbnail="{{$dummy->getThumbnail()}}"
                        data-thumbnail_lg="{{$dummy->getThumbnail_lg()}}"
                        data-name="{{$dummy->getName()}}">BUY ME</button>
            </li>
        @endforeach
    </ul>

    <div id="cart-container" style="margin-right: 0px; display: block;">
        <div class="proceed btn-group btn-group-justified">
            <a class="btn btn-two btn-lg" id="back">{{ Lang::get("boukem.keep_shopping") }}</a>
            <a class="btn btn-three btn-lg" id="checkout" href="{{ url("/dev/cart") }}">{{ Lang::get("boukem.check_out") }}</a>
        </div>
        <div id="cart-items">
            <ul class="cart-items-list">

            </ul>
        </div>
    </div>
@endsection


@section("scripts")
    <script src="/js/cart-drawer.js"></script>
@endsection