{{-- SECTION: MIXED --}}

<section class="mixed-section color-one">

    @if ($showTab)
        @include("site.homepage._tab", ["tabTitle" => $layoutData["mixed"]["tabTitle"]])
    @endif

        <div class="ui grid container">

            {{-- Product section. --}}
            <div class="eleven wide column">
                <div class="ui three cards">
                @foreach($layoutData["mixed"]["products"] as $product)
                    <div class="card">
                        <a href="{{ route('product', ['slug' => $product->slug]) }}">
                            <img class="ui tiny image center-block" src="{{ Products::imgFeatured($product->id) }}" alt="{{ Products::imgFeatured($product->id) }}"/>
                        </a>

                        <div class="content">
                            <a href="{{ route('product', ['slug' => $product->slug]) }}" class="header">
                                {{ $product->localization->name }}
                            </a>

                            <div class="meta">
                                <span>{{ str_limit(strip_tags($product->localization->short_description), 100, "...") }}</span>
                            </div>
                        </div>

                        <div class="extra content">
                            Rating:
                            <div class="ui star rating" data-rating="4"></div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>

            {{-- Search and Widget section. --}}
            <div class="four wide column large screen only">
                <div class="widget">
                    <form action="{{ route('search') }}" method="get">
                        <div class="ui fluid action input">
                            <input type="search" name="q" id="searchBar" value="" autocomplete="off" spellcheck="false" placeholder="@lang('boukem.search')">
                            <button type="submit" class="ui button">@lang("boukem.search")</button>
                        </div>
                    </form>
                </div>

                <div class="widget">
                    <h3 class="ui header white">{{ Lang::get("boukem.shortcuts") }}</h3>
                </div>

                <ul class="categories highlight">
                    {{--TODO : Nothing for now ...--}}
                </ul>
            </div>
        </div>

        <br/>
        <br/>
</section>