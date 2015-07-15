{{-- HEADER. --}}


<div class="container-fluid header">
    <div class="row">
        <div class="col-xs-2 visible-xs-block pull-left wrapper-xs">
            <div class="btn-group ">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Menu <span class="caret"></span>
                </button>
                <span class="sr-only">{{ Lang::get("boukem.toggle_nav") }}</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#">{{ Lang::get("boukem.categories") }}</a></li>
                    <li><a href="#">{{ Lang::get("boukem.brands") }}</a></li>
                    <li><a href="#">{{ Lang::get("boukem.health_issues") }}</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">{{ Lang::get("boukem.featured_products") }}</a></li>
                    <li><a href="#">{{ Lang::get("boukem.deals") }}</a></li>
                </ul>
            </div>
        </div>

        <a href="{{ url("/") }}">
            <div class="col-md-2 col-sm-3 col-xs-8" id="nav-left">
                <span class="sr-only">{{ Lang::get("boukem.back_to_home") }}</span>
                &nbsp; &nbsp;
            </div>
        </a>

        <div class="col-xs-2 visible-xs-block pull-right view-cart-wrapper-xs">
            <button class="btn btn-default">
                <a href="#" class="view-cart">
                    <i class="fa fa-shopping-cart icon-cart"></i>
                    <span class="badge cart_badge">0</span>

                    <span class="sr-only">elements</span>
                </a>

                <span class="sr-only">{{ Lang::get("boukem.view_cart") }}</span>
            </button>
        </div>

        <div class="col-md-7 col-sm-6" id="searchBarWrapper">
            <div class="form-group form-inline">
                <div class="col-md-12 col-sm-12">
                    <form class="inline-block searchBar-form col-md-11" method="get" action="{{ route('search') }}">
                        <div class="btn-group search-filter hidden-sm hidden-xs">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Lang::get("boukem.all") }} <span class="caret"></span>
                            </button>
                            <span class="sr-only">{{ Lang::get("boukem.toggle_nav") }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">{{ Lang::get("boukem.all") }}</a></li>
                                <li><a href="#">{{ Lang::get("boukem.categories") }}</a></li>
                                <li><a href="#">{{ Lang::get("boukem.brands") }}</a></li>
                                <li><a href="#">{{ Lang::get("boukem.health_issues") }}</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">{{ Lang::get("boukem.featured_products") }}</a></li>
                                <li><a href="#">{{ Lang::get("boukem.deals") }}</a></li>
                            </ul>
                        </div>

                        <span class="sr-only">Search bar</span>
                        <input type="search" class="form-control" name="q" id="searchBar" value="" autocomplete="off" spellcheck="false">
                        <button class="btn btn-primary search" type="submit"><i class="fa fa-search"></i>
		                             <span class="sr-only">
		                                 {{ Lang::get("boukem.search") }}
		                             </span>
                        </button>
                    </form>


                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-3 hidden-xs" id="nav-right">
            <ul>
                <li class="border-bottom-hover header-account-button">
                    <div class="ui dropdown">
                        <div class="text">
                            <i class="icon fa fa-user"></i> {{ Lang::get("boukem.my") }} {{ Lang::get("boukem.account") }}
                        </div>
                        <i class="dropdown icon"></i>
                        <div class="menu">

                            @if(Auth::guest())
                                <div class="item">
                                    <button class="btn btn-success color-one text-center center-block full-width">
                                        {{ Lang::get("boukem.sign_in") }}
                                    </button>
                                </div>

                                <div class="item no-hover">
                                    <div class="description">
                                        {{ Lang::get("boukem.no_account") }} <a href="#">{{ Lang::get("boukem.sign_up") }} !</a>
                                    </div>
                                </div>
                            @else
                                <div class="item">
                                    <a href="#">Your orders</a>
                                </div>

                                <div class="item">
                                    <a href="#">Settings</a>
                                </div>

                                <div class="divider"></div>

                                <div class="item">
                                    <button class="btn btn-default color-one text-center center-block full-width">
                                        {{ Lang::get("boukem.sign_out") }}
                                    </button>
                                </div>
                            @endif

                            <div class="divider"></div>

                            <div class="item">
                                <a href="{{ action("WishlistController@index") }}" class="wishlist-button">
                                    <div class="text-center center-block">
                                        {{ Lang::get("boukem.wishlist_has") }}
                                    </div>
                                    <span class="text-center center-block no-decoration" style="padding:0.5em 0 ">
                                        <span class="badge wishlist_badge">0</span> items.
                                    </span>
                                </a>
                            </div>

                        </div>
                    </div>
                </li>
                <li class="border color-one">
                    <a class="view-cart">
                        <button class="btn btn-default" id="view-cart-wrapper">
                            <i class="fa fa-shopping-cart icon-cart"></i>
                            <span id="cart-description">{{ " " . Lang::get("boukem.cart") . " " }}</span>
                            <span class="badge cart_badge">0</span>
                            <span class="sr-only">items</span>
                        </button>
                    </a>
                </li>
            </ul>


        </div>
    </div>


    <div class="row hidden-xs header-banner">
        <div class="col-md-2 col-sm-3">
            <div class="ui pointing dropdown link item fluid text-center">
                <span class="text header-banner-align">
                    <span class="light">Shop by</span><strong class="bold"> Categories</strong>
                    <i class="fa fa-caret-down pull-right" style="line-height: 53px"></i>
                </span>
                <div class="menu fluid">
                    <div class="item">
                        <span class="text">Clothing</span>
                        <i class="fa fa-caret-right pull-right"></i>
                        <div class="menu">
                            <div class="semantic-header">Mens</div>
                            <div class="item">Shirts</div>
                            <div class="item">Pants</div>
                            <div class="item">Jeans</div>
                            <div class="item">Shoes</div>
                            <div class="divider"></div>
                            <div class="semantic-header">Womens</div>
                            <div class="item">Dresses</div>
                            <div class="item">Shoes</div>
                            <div class="item">Bags</div>
                        </div>
                    </div>
                    <div class="item">Home Goods</div>
                    <div class="item">Bedroom</div>
                    <div class="divider"></div>
                    <div class="semantic-header">Order</div>
                    <div class="item">Status</div>
                    <div class="item">Cancellations</div>
                </div>
            </div>

            {{--<div class="btn-group shop-filter">--}}
                {{--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{--Shop by <span> <strong> Categories </strong> </span><span class="caret "></span>--}}
                {{--</button>--}}
                {{--<ul class="dropdown-menu">--}}
                    {{--<li><a href="# ">Alimentation</a></li>--}}
                    {{--<li><a href="# ">Antioxydants</a></li>--}}
                    {{--<li><a href="# ">Aromathératpie</a></li>--}}
                    {{--<li><a href="# ">Cosmétique</a></li>--}}
                    {{--<li><a href="# ">Enzymes</a></li>--}}
                    {{--<li><a href="# ">Genmothérapie</a></li>--}}
                    {{--<li><a href="# ">Homeopathie</a></li>--}}
                {{--</ul>--}}
            {{--</div>--}}
        </div>


        <ul class="col-md-10">
            <li class="inline-block ">
                <button type="button " class="btn btn-link "><a href="# ">Back to store</a></button>
            </li>
            <li class="inline-block ">
                <button type="button " class="btn btn-link "><a href="# ">Today's deal</a></button>
            </li>
            <li class="inline-block ">
                <button type="button " class="btn btn-link "><a href="# ">Contact</a></button>
            </li>
        </ul>

    </div>

</div>