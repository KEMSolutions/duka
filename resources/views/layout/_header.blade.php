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

        <a href="{{ route("home") }}">
            <div class="col-md-2 col-sm-3 col-xs-8" id="nav-left" style="background: url({{ Store::logo() }})  no-repeat center">
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
                            <div class="ui dropdown dropdown-select top left pointing">
                                <div class="text">{{ Lang::get("boukem.all") }} </div>
                                <i class="fa fa-caret-down"></i>
                                <div class="menu">
                                    <div class="item">{{ Lang::get("boukem.all") }}</div>
                                    <div class="item">{{ Lang::get("boukem.categories") }}</div>
                                    <div class="item">{{ Lang::get("boukem.brands") }}</div>
                                    <div class="item">{{ Lang::get("boukem.health_issues") }}</div>
                                    <div class="divider"></div>
                                    <div class="item">{{ Lang::get("boukem.featured_products") }}</div>
                                    <div class="item">{{ Lang::get("boukem.deals") }}</div>
                                </div>
                            </div>
                        </div>

                        <span class="sr-only">Search bar</span>
                        <input type="search" class="form-control" name="q" id="searchBar" value="" autocomplete="off" spellcheck="false">
                        <button class="btn btn-one search" type="submit"><i class="fa fa-search"></i>
		                             <span class="sr-only">
		                                 {{ Lang::get("boukem.search") }}
		                             </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{--End of searchBarWrapper--}}

        <div class="col-md-3 col-sm-3 hidden-xs" id="nav-right">
            <ul>
                <li class="btn btn-five">
                    <div class="ui top left pointing dropdown dropdown-no-select">
                        <div class="text">
                            <i class="icon fa fa-user"></i> {{ Lang::get("boukem.my") }} {{ Lang::get("boukem.account") }}
                        </div>
                        <i class="dropdown icon"></i>
                        <div class="menu">

                            @if(Auth::guest())
                                <div class="item no-hover">
                                    <a href="{{ route("auth.login") }}">
                                        <button class="btn btn-success color-one text-center center-block full-width">
                                            {{ Lang::get("boukem.log_in") }}
                                        </button>
                                    </a>
                                </div>

                                <div class="item no-hover">
                                    <div class="description">
                                        {{ Lang::get("boukem.no_account") }} <a href="{{ route("auth.register") }}">{{ Lang::get("boukem.sign_up") }} !</a>
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
                                    <a href="{{ route("auth.logout") }}">
                                        <button class="btn btn-default color-one text-center center-block full-width">
                                            {{ Lang::get("boukem.log_out") }}
                                        </button>
                                    </a>
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
                <li class="color-one border-color-one" style="border-radius: 3px">
                    <a class="view-cart">
                        <button class="btn btn-one" id="view-cart-wrapper">
                            <i class="fa fa-shopping-cart icon-cart color-one-text"></i>
                            <span id="cart-description">{{ " " . Lang::get("boukem.cart") . " " }}</span>
                            <span class="badge cart_badge">0</span>
                            <span class="sr-only">items</span>
                        </button>
                    </a>
                </li>
            </ul>
        </div>
        {{--End of nav right--}}
    </div>
    {{--End of first row--}}


    <div class="row hidden-xs header-banner color-one">
        <div class="col-md-2 col-sm-3 border-right">
            <div class="ui pointing dropdown dropdown-no-select link item fluid text-center">
                <span class="text header-banner-align">
                    <span class="light">{{ Lang::get("boukem.shop_by") }}</span><strong class="bold"> {{ Lang::get("boukem.categories") }}</strong>
                    <i class="fa fa-caret-down pull-right" style="line-height: 53px"></i>
                </span>
                <div class="menu fluid">
                    @foreach(Categories::getAllCategories() as $category)
                        {{-- If there are any children category, list them under the main category --}}
                            @if(count($category->children) > 0)
                            <div class="item">
                                <span class="text">{{ $category->name }}</span>
                                <i class="fa fa-caret-right pull-right"></i>
                                <div class="menu">
                                    @foreach($category->children as $children)
                                        <div class="item">
                                            <a class="dark" href="/{{ Localization::getCurrentLocale() }}/cat/{{ $children->slug }}">{{ $children->name }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                                <div class="item">
                                    @if(isset($category->slug) && isset($category->name))
                                        <a class="dark no-decoration"
                                           href="/{{ Localization::getCurrentLocale() }}/cat/{{ $category->slug }}">
                                            <div class="item">
                                                {{ $category->name }}
                                            </div>

                                        </a>
                                    @endif
                                </div>
                            @endif
                    @endforeach

                </div>
            </div>
        </div>

        <ul class="col-md-10 header-banner-align">

            {{-- Back to Store's website --}}
            @if (strlen(Store::info()->url))
                <li class="inline-block ">
                    <button type="button" class="btn btn-link">
                        <a href="{{ Store::info()->url }}">Back to site</a>
                    </button>
                </li>
            @endif

            {{-- Links to custom pages --}}
            @if (count(Pages::all()))
                @foreach (Pages::all() as $page)
                    <li class="inline-block ">
                        <button type="button" class="btn btn-link">
                            <a href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->title }}</a>
                        </button>
                    </li>
                @endforeach
            @endif

            {{-- Store contact info --}}
            <li class="inline-block ">
                <div class="ui pointing dropdown-select dropdown top left item fluid text-center">
                <span class="text">
                    {{ Lang::get("boukem.contact") }}
                    <i class="fa fa-caret-down pull-right" style="line-height: 53px"></i>
                </span>
                    <div class="menu fluid">
                        <div class="item">
                            <i class="fa fa-phone icon"></i>
                            {{ Store::info()->support->phone }}
                        </div>
                        <div class="divider"></div>
                        <div class="item">
                            <i class="fa fa-envelope-o icon"></i>
                            <a href="mailto:{{ Store::info()->support->email }}" class="dark">{{ Store::info()->support->email }}</a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    {{--End of second row--}}

</div>
{{--End of header--}}
