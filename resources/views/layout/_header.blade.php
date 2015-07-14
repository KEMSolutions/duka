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

                <span class="sr-only">View cart</span>
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
                <li>
                    <div class="ui dropdown">
                        <div class="text">File</div>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                            <div class="item">New</div>
                            <div class="item">
                                <span class="description">ctrl + o</span>
                                Open...
                            </div>
                            <div class="item">
                                <span class="description">ctrl + s</span>
                                Save as...
                            </div>
                            <div class="item">
                                <span class="description">ctrl + r</span>
                                Rename
                            </div>
                            <div class="item">Make a copy</div>
                            <div class="item">
                                <i class="folder icon"></i>
                                Move to folder
                            </div>
                            <div class="item">
                                <i class="trash icon"></i>
                                Move to trash
                            </div>
                            <div class="divider"></div>
                            <div class="item">Download As...</div>
                            <div class="item">
                                <i class="dropdown icon"></i>
                                Publish To Web
                                <div class="menu">
                                    <div class="item">Google Docs</div>
                                    <div class="item">Google Drive</div>
                                    <div class="item">Dropbox</div>
                                    <div class="item">Adobe Creative Cloud</div>
                                    <div class="item">Private FTP</div>
                                    <div class="item">Another Service...</div>
                                </div>
                            </div>
                            <div class="item">E-mail Collaborators</div>
                        </div>
                    </div>
                    {{--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                        {{--{{ Lang::get("boukem.my") }} <span><strong>{{ Lang::get("boukem.account") }}</strong></span> <span class="caret"></span>--}}
                    {{--</button>--}}
                    {{--<span class="sr-only">Toggle Dropdown</span>--}}
                    {{--</button>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--@if (Auth::guest())--}}
                            {{--<li><a href="{{ action("WishlistController@index") }}">{{ Lang::get("boukem.wishlist") }}  <span class="badge wishlist_badge">0</span></a></li>--}}
                            {{--<li role="separator" class="divider"></li>--}}
                            {{--<li><a href="#">{{ Lang::get("boukem.sign_up") }}</a></li>--}}
                            {{--<li><a href="#">{{ Lang::get("boukem.sign_in") }}</a></li>--}}
                        {{--@else--}}
                            {{--<li><a href="#">Your orders</a></li>--}}
                            {{--<li><a href="{{ action("WishlistController@index") }}"> {{ Lang::get("boukem.wishlist") }}  <span class="badge wishlist_badge">0</span></a></li>--}}
                            {{--<li><a href="#">Account Settings</a></li>--}}
                            {{--<li role="separator" class="divider"></li>--}}
                            {{--<li><a href="{{ url('/auth/logout') }}">{{ Lang::get("boukem.sign_out") }}</a></li>--}}
                        {{--@endif--}}

                    {{--</ul>--}}
                </li>
                <li>
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