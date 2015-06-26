{{-- HEADER. --}}


<div class="container-fluid header">
    <div class="row">
        <div class="col-xs-2 visible-xs-block pull-left wrapper-xs">
            <div class="btn-group ">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Menu <span class="caret"></span>
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
        </div>

        <a href="{{ url("/") }}">
            <div class="col-md-2 col-sm-3 col-xs-8" id="nav-left">
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
                    <p class="inline-block hidden-xs hidden-sm col-md-1 text-center" id="search_in">{{ Lang::get("boukem.search_in") }}</p>

                    <form class="inline-block searchBar-form col-md-11" method="get" action="{{ route('search') }}">
                    <div class="btn-group search-filter hidden-sm hidden-xs">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Lang::get("boukem.featured_products") }} <span class="caret"></span>
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