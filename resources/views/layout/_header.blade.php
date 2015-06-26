{{--<header>--}}
    {{--<nav class="navbar navbar-default navbar-white" id="navbar">--}}
        {{--<div class="container-fluid">--}}

            {{--<div class="row">--}}
                {{--<div class="col-md-1">--}}
                    {{--<div class="navbar-header">--}}
                        {{--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">--}}
                            {{--<span class="sr-only">{{ Lang::get("boukem.home") }}</span>--}}
                            {{--<span class="icon-bar"></span>--}}
                            {{--<span class="icon-bar"></span>--}}
                            {{--<span class="icon-bar"></span>--}}
                        {{--</button>--}}
                        {{--<a class="navbar-brand" href="{{url("/")}}">--}}
                            {{--<img src="{{url("/") . "/img/logo.png"}}" class="img-responsive" alt="">--}}
                        {{--</a>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="navbar-collapse collapse">--}}
                    {{--<div class="col-md-8">--}}
                        {{--<ul class="nav navbar-nav full-width">--}}
                            {{--<div class="col-md-3">--}}
                                {{--<li><span class="navbar-text">Search in:  </span> <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>--}}
                                    {{--<ul class="dropdown-menu">--}}
                                        {{--<li><a href="#">Action</a></li>--}}
                                        {{--<li><a href="#">Another action</a></li>--}}
                                        {{--<li><a href="#">Something else here</a></li>--}}
                                        {{--<li role="separator" class="divider"></li>--}}
                                        {{--<li><a href="#">Separated link</a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}


                            {{--</div>--}}
                            {{--<div class="col-md-9">--}}
                                {{--<li>--}}
                                    {{--<form class="navbar-form" role="search">--}}
                                        {{--<div class="form-group" style="width: 90%">--}}
                                            {{--<input type="text" class="form-control full-width" placeholder="Search">--}}
                                        {{--</div>--}}
                                        {{--<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>--}}
                                    {{--</form>--}}

                                {{--</li>--}}
                            {{--</div>--}}
                        {{--</ul>--}}
                    {{--</div>--}}


                    {{--<div class="col-md-3">--}}
                        {{--<ul class="nav navbar-nav navbar-right">--}}

                            {{--@if (Auth::guest())--}}
                                {{--<li class="dropdown">--}}
                                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">--}}
                                        {{--Hello, Sign in--}}
                                        {{--<span class="caret"></span></a>--}}
                                    {{--<ul class="dropdown-menu" role="menu">--}}
                                        {{--<li><a href="{{ url('/auth/signin') }}">Sign in</a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                            {{--@else--}}
                                {{--<li class="dropdown">--}}
                                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>--}}
                                    {{--<ul class="dropdown-menu" role="menu">--}}
                                        {{--<li><a href="{{ url('/auth/logout') }}">Logout</a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}

                            {{--@endif--}}

                            {{--<li>--}}
                                {{--<a class="view-cart"><i class="fa fa-shopping-cart icon-cart"></i> {{ " " . Lang::get("boukem.cart") . " " }}<span class="badge" id="cart_badge"> 0 </span></a>--}}
                            {{--</li>--}}

                        {{--</ul>--}}
                    {{--</div>--}}

                {{--</div>--}}

            {{--</div>--}}

            {{--<div class="row">--}}
                {{--<ul class="navbar navbar-nav">--}}
                    {{--<li><a href="{{ action("LayoutController@home") }}"> {{ Lang::get("boukem.home") }} </a></li>--}}

                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Lang::get("boukem.contact") }}</a>--}}
                        {{--<ul class="dropdown-menu">--}}
                            {{--<li>--}}
                                {{--<a href="tel:18442763434" class="sign-up.html"><i class="fa fa-phone fa-fw"></i> 1-844-276-3434 </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="mailto:#" class="sign-up.html"><i class="fa fa-envelope fa-fw"></i>Temporary Email </a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li><a href="#">LINK 2</a></li>--}}
                    {{--<li><a href="#">LINK 3</a></li>--}}


                {{--</ul>--}}

            {{--</div>--}}

        {{--</div>--}}
    {{--</nav>--}}
{{--</header>--}}

<div class="container-fluid header">
    <div class="row">
        <a href="#">
            <div class="col-md-2 col-sm-3 col-xs-10" id="nav-left">
                &nbsp; &nbsp;
            </div>
        </a>

        <div class="col-xs-2 visible-xs-block pull-right view-cart-wrapper-xs">
            <button class="btn btn-default">
                <a href="#" class="view-cart"><i class="fa fa-shopping-cart icon-cart"></i> <span class="badge cart_badge">0</span></a>
            </button>

        </div>
        <div class="col-md-7 col-sm-6" id="searchBarWrapper">
            <div class="form-group form-inline">
                <div class="col-md-12 col-sm-12">
                    <p class="inline-block hidden-xs">{{ Lang::get("boukem.search_in") }}</p>

                    <form class="inline-block searchBar-form" method="get" action="{{ route('search') }}">
                    <div class="btn-group search-filter hidden-xs">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Lang::get("boukem.all") }} <span class="caret"></span>
                        </button>
                        <span class="sr-only">Toggle Dropdown</span>
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


                        <input type="search" class="form-control" name="q" id="searchBar" value="" autocomplete="off" spellcheck="false">
                        <button class="btn btn-primary search" type="submit"><i class="fa fa-search"></i><span class="sr-only">{{ Lang::get("boukem.search") }}</span></button>
                    </form>

                </div>

            </div>

        </div>

        <div class="col-md-3 col-sm-3 hidden-xs" id="nav-right">
            <ul>
                <li>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Lang::get("boukem.your") }} <span><strong>{{ Lang::get("boukem.account") }}</strong></span> <span class="caret"></span>
                    </button>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        @if (Auth::guest())
                            <li><a href="#">{{ Lang::get("boukem.sign_up") }}</a></li>
                            <li><a href="#">{{ Lang::get("boukem.sign_in") }}</a></li>
                        @else
                            <li><a href="{{ url('/auth/logout') }}">{{ Lang::get("boukem.log_out") }}</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#">Your orders</a></li>
                            <li><a href="#">Account Settings</a></li>
                        @endif

                    </ul>
                </li>
                <li>
                    <a class="view-cart">
                        <button class="btn btn-default" id="view-cart-wrapper">
                            <i class="fa fa-shopping-cart icon-cart"></i> <span id="cart-description">{{ " " . Lang::get("boukem.cart") . " " }}</span> <span class="badge cart_badge">0</span>
                        </button>
                    </a>
                </li>
            </ul>


        </div>
    </div>


    <div class="row hidden-xs">
        <div class="col-md-2 col-sm-3">
            <div class="btn-group shop-filter">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Shop by <span> <strong> Categories </strong> </span><span class="caret "></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="# ">Alimentation</a></li>
                    <li><a href="# ">Antioxydants</a></li>
                    <li><a href="# ">Aromathératpie</a></li>
                    <li><a href="# ">Cosmétique</a></li>
                    <li><a href="# ">Enzymes</a></li>
                    <li><a href="# ">Genmothérapie</a></li>
                    <li><a href="# ">Homeopathie</a></li>
                </ul>
            </div>
        </div>


        <ul>
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