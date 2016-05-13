@include("layout._header_helpers")


<div class="ui sidebar inverted wide vertical menu mobile-main-menu">

    <a class="right item " href="{{ route("home") }}">
        {{ Lang::get("boukem.home") }}
    </a><!-- Item (Home) -->


    <div class="ui scrolling dropdown item ">
        @lang('boukem.health_conditions')
        <i class="dropdown icon"></i>

        <div class="menu">
            @foreach(Categories::getAllConditions() as $condition)
                {!! generateMenuItem($condition) !!}
            @endforeach
        </div><!-- Menu -->
    </div><!-- Item (Conditions) -->

    <div class="ui scrolling dropdown item ">
        @lang('boukem.categories')
        <i class="dropdown icon"></i>

        <div class="menu">
            @foreach(Categories::getAllCategories() as $category)
                {!! generateMenuItem($category) !!}
            @endforeach
        </div><!-- Menu -->
    </div><!-- Item (Categories) -->


    <div class="ui scrolling dropdown item ">
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
            <a class="item " href="{{ route('page', ['slug' => $page->slug]) }}">
                {{ $page->title }}
            </a>
        @endforeach
    @endif


    {{-- Links to blog. --}}
    @if(count(Store::info()->blogs) > 0)
        <a class="item " href="{{ action('BlogController@index') }}">
            @lang('boukem.blog')
        </a>
    @endif


    <div class="ui scrolling dropdown item ">
        @lang('boukem.contact')
        <i class="dropdown icon"></i>

        <div class="menu">
            @if (Store::info()->support->phone && Store::info()->support->phone !== "")
                <div class="item">
                    <i class="phone icon"></i>
                    <a href="tel:{{ Store::info()->support->phone->number }}">
                        {{ Store::info()->support->phone->vanity }}
                    </a>
                </div>
            @endif
            @if  (Store::info()->support->email && Store::info()->support->email !== "")
                <div class="item">
                    <i class="mail outline icon"></i>
                    <a href="mailto:{{ Store::info()->support->email }}">
                        {{ Store::info()->support->email }}
                    </a>
                </div>
            @endif
        </div><!-- Menu -->
    </div><!-- Item (Contact) -->
</div> <!-- Menu -->