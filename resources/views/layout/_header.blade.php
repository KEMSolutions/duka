{{-- HEADER. --}}

{{----}}

    {{-- FUTURE DESCRIPTION OF HEADER GOES HERE.--}}

{{----}}

@include("layout._header_helpers")

<nav class="supertop">
    <div class="ui container">
        <div class="ui sixteen column padded stackable grid">
            <div class="row">
                <div class="five wide column">

                    {{-- Include a condition here to display a back to website --}}

                    {{--<a href="http://www.lamoisson.com" class="ui left labeled icon button">--}}
                        {{--<i class="left arrow icon"></i>--}}
                        {{--@lang('boukem.back_to_main')--}}
                    {{--</a>--}}

                </div>
                <div class="six wide column">
                    <form action="{{ route('search') }}" method="get">
                        {{--<div class="ui fluid icon input">--}}
                            {{--<input type="text" name="q" placeholder="@lang("boukem.search")">--}}
                            {{--<i class="inverted circular search link icon" onclick="$(this).closest('form').submit();"></i>--}}
                        {{--</div>--}}

                        <div class="ui fluid action input">
                            <input type="text" name="q" placeholder="@lang("boukem.search")">
                            <button class="ui button" onclick="$(this).closest('form').submit();">
                                <i class="search button icon"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="five wide right aligned column fluid">

                    @if(Auth::check())
                        <div class="ui button">
                            <div class="ui simple dropdown item">
                                <i class="user icon"></i>
                                <div class="menu">
                                    <a class="item" href="{{ route('auth.account') }}">
                                        @lang("boukem.settings")
                                    </a>
                                    <a class="item" href="{{ route("auth.logout") }}">
                                        @lang("boukem.log_out")
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="view-cart ui labeled button" tabindex="0">
                        <div class="ui button">
                            <i class="cart icon"></i> {{ Lang::get("boukem.cart") }}
                        </div>
                        <a class="view-cart ui basic left pointing label cart_badge">0</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<nav class="mainmenu">
    <div class="ui container">
        <div class="ui sixteen column padded stackable grid">
            <div class="row">
                <div class="three wide column">
                    <a href="/"><img src="{{ Store::logo() }}" class="ui image" alt="{{ Store::info()->url }}"></a>
                </div>
                <div class="thirteen wide bottom aligned column">
                    <div class="ui secondary stackable menu topmenu">

                        <a class="active right item" href="{{ route("home") }}">
                            {{ Lang::get("boukem.home") }}
                        </a><!-- Item (Home) -->

                        <div class="ui scrolling dropdown item">
                            @lang('boukem.categories')
                            <i class="dropdown icon"></i>

                            <div class="menu">
                                @foreach(Categories::getAllCategories() as $category)
                                    {!! generateMenuItem($category) !!}
                                @endforeach
                            </div><!-- Menu -->
                        </div><!-- Item (Categories) -->

                        <div class="ui scrolling dropdown item">
                            @lang('boukem.brands')
                            <i class="dropdown icon"></i>

                            <div class="menu">
                                @foreach(Brands::getAllBrands() as $brand)
                                    {!! generateMenuItem($brand) !!}
                                @endforeach
                            </div><!-- Menu -->
                        </div><!-- Item (Brands) -->

                        {{-- Links to custom pages. --}}
                        @if (count(Pages::all()))
                            @foreach (Pages::all() as $page)
                                <a class="item" href="{{ route('page', ['slug' => $page->slug]) }}">
                                    {{ $page->title }}
                                </a>
                            @endforeach
                        @endif

                        @if(count(Store::info()->blogs) > 0)
                            <a class="item" href="{{ action('BlogController@index') }}">
                                @lang('boukem.blog')
                            </a>
                        @endif

                        <div class="ui top right pointing dropdown item" style="margin-left: 0 !important">
                            @lang('boukem.contact')
                            <i class="dropdown icon"></i>

                            <div class="menu">
                                @if (Store::info()->support->phone)
                                    <div class="item">
                                        <i class="fa fa-phone icon"></i>
                                        <a href="tel:{{ Store::info()->support->phone->number }}">
                                            {{ Store::info()->support->phone->vanity }}
                                        </a>
                                    </div>
                                @endif
                                <div class="item">
                                    <i class="fa fa-envelope-o icon"></i>
                                    <a href="mailto:{{ Store::info()->support->email }}">
                                        {{ Store::info()->support->email }}
                                    </a>
                                </div>
                            </div><!-- Menu -->
                        </div><!-- Item (Contact) -->

                    </div> <!-- Menu -->
                </div><!-- Column -->
            </div><!-- Row -->
        </div><!-- Grid -->
    </div><!-- Container -->
</nav><!-- .mainmenu -->
