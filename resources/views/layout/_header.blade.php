{{-- HEADER. --}}

{{--

    The header is built using semantic ui grid system.
    It is a stackable vertically aligned and padded 16 column grid.
    There are 2 rows, the second one hidden when the viewport is less than 768px.

--}}
<div class="ui stackable vertically padded grid middle aligned">

    {{-- First row. --}}
    <div class="row">
        <div class="three wide column">
            <a href="{{ route("home") }}">
                <div class="sixteen wide column" style="background: url({{ Store::logo() }})  no-repeat center; height: 60px">
                    <span class="sr-only">{{ Lang::get("boukem.back_to_home") }}</span>
                </div>
            </a>
        </div>


        <div class="eight wide column">
            <form action="{{ route('search') }}" method="get">
                <div class="ui fluid action input">
                    <input type="search" name="q" id="searchBar" value="" autocomplete="off" spellcheck="false" placeholder="@lang('boukem.search')">

                    <select class="ui selection dropdown dropdown-select compact">
                        <option value="@lang("boukem.all_categories")">@lang("boukem.all_categories")</option>
                        <option value="@lang("boukem.brands")">@lang("boukem.brands")</option>
                        <option value="@lang("boukem.health_issues")">@lang("boukem.health_issues")</option>
                        <option value="@lang("boukem.featured_products")">@lang("boukem.featured_products")</option>
                    </select>

                    <button type="submit" class="ui button">@lang("boukem.search")</button>
                </div>
            </form>
        </div>

        <div class="four wide column right floated text-center">

            {{-- Mobile only categories menu. --}}
                <div class="ui icon btn btn-one visible-xs-inline-block">
                    <div class="ui dropdown dropdown-select pointing top left">
                        <div class="text">
                            {{ Lang::get("boukem.shop_by") }}{{ Lang::get("boukem.categories") }}
                        </div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            @foreach(Categories::getAllCategories() as $category)
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
            {{-- End of mobile only categories menu. --}}


            <div class="ui icon btn btn-five">
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
                                <a href="{{ route('auth.account') }}">Settings</a>
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
            </div>

            <a class="view-cart">
                <button class="ui btn btn-one" id="view-cart-wrapper">
                    <i class="fa fa-shopping-cart icon-cart color-one-text"></i>
                    <span id="cart-description">{{ " " . Lang::get("boukem.cart") . " " }}</span>
                    <span class="badge cart_badge">0</span>
                    <span class="sr-only">items</span>
                </button>
            </a>

        </div>
    </div>
    {{-- End of fist row. --}}

    {{-- Second row. --}}
    <div class="row header-banner color-one hidden-xs">

        <div class="three wide column text-center border-right">
            <div class="ui dropdown dropdown-no-select pointing item">

                <span class="text">
                    <span class="light">{{ Lang::get("boukem.shop_by") }}</span><strong class="bold"> {{ Lang::get("boukem.categories") }}</strong>
                    <span style="font-size: 0.685rem;color: #fff;padding: 0 0.585rem;">&#x25BC;</span>
                </span>

                <div class="menu fluid">
                    @foreach(Categories::getAllCategories() as $category)
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

            {{-- Back to store link. --}}
            @if(strlen(Store::info()->url))
                <button type="button" class="btn btn-link">
                    <a href="{{ Store::info()->url }}">Back to site</a>
                </button>
            @endif

            {{-- Links to custom pages. --}}
            @if (count(Pages::all()))
                @foreach (Pages::all() as $page)
                    <button type="button" class="btn btn-link">
                        <a href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->title }}</a>
                    </button>
                @endforeach
            @endif

        {{-- Store contact info. --}}
            <button type="button" class="btn btn-link">
                <div class="ui pointing dropdown dropdown-select top left white">
                    <span class="text">
                        {{ Lang::get("boukem.contact") }}
                        <i class="caret down icon"></i>
                    </span>

                    <div class="menu fluid">
                        <div class="item">
                            <i class="fa fa-phone icon"></i>
                            {{ Store::info()->support->phone->number }}
                        </div>
                        <div class="divider"></div>
                        <div class="item">
                            <i class="fa fa-envelope-o icon"></i>
                            <a href="mailto:{{ Store::info()->support->email }}" class="dark">{{ Store::info()->support->email }}</a>
                        </div>
                    </div>
                </div>
            </button>
    </div>
    {{-- End of second row. --}}

</div>
{{-- End of header. --}}
