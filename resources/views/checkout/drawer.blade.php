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
            <a class="btn btn-two btn-lg" id="back">Continuer le magasinage</a>
            <a class="btn btn-three btn-lg" id="checkout" href="#">Passer à la caisse</a>
        </div>
            {{--<a class="btn btn-three btn-lg" id="checkout" href="/cart/index">Passer à la caisse</a>	</div>--}}
        <div id="cart-items">
            <ul class="cart-items-list">
                {{--<li class="w-box animated bounceInDown" data-product="6484" data-quantity="1">--}}
                    {{--<div class="col-xs-3 text-center">--}}
                        {{--<img src="http://placehold.it/100x160" class="img-responsive">--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-9 no-padding-left">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-10">--}}
                                {{--<h3 class="product-name">Perfect probiotic - 120 g</h3>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-2">--}}
                                {{--<h4 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">Remove from cart</span></i></h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-8">--}}
                                {{--<div class="input-group">--}}
                                    {{--<input type="number" value="1" class="quantity form-control input-sm" min="1" step="1">--}}
                                    {{--<span class="update_quantity_indicator input-group-addon ">--}}
                                        {{--<i class="fa" hidden=""><span class="sr-only">Update quantity</span></i>--}}
                                    {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-4 product-price text-right" data-price="34.99">$34.99</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</li>--}}

                {{--<li class="w-box animated bounceInDown" data-product="6484" data-quantity="1">--}}
                    {{--<div class="col-xs-3 text-center">--}}
                        {{--<img src="http://placehold.it/100x160" class="img-responsive">--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-9 no-padding-left">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-10">--}}
                                {{--<h3 class="product-name">Perfect probiotic - 120 g</h3>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-2">--}}
                                {{--<h4 class="text-right"><i class="fa fa-trash fa-1 close-button"><span class="sr-only">Remove from cart</span></i></h4>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-8">--}}
                                {{--<div class="input-group">--}}
                                    {{--<input type="number" value="1" class="quantity form-control input-sm" min="1" step="1">--}}
                                    {{--<span class="input-group-addon update_quantity_indicator">--}}
                                        {{--<i class="fa" hidden=""><span class="sr-only">Update quantity</span></i>--}}
                                    {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-4 product-price text-right" data-price="34.99">$34.99</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</li>--}}



            </ul>
        </div>
    </div>
@endsection


@section("scripts")
    <script src="/js/cart-drawer.js"></script>
@endsection