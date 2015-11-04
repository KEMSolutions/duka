{{-- SECTION: MIXED --}}

<section class="mixed-section color-one">

    @if ($showTab)
        @include("site.homepage._tab", ["tabTitle" => $layoutData["mixed"]["tabTitle"]])
    @endif

    <div class="ui grid container">

        {{-- Product section. --}}
        <div class="sixteen wide column">
            <div class="ui four cards stackable">
                @foreach($layoutData["mixed"]["products"] as $product)

                    <div class="card">
                        <a href="{{ route('product', ['slug' => $product->slug]) }}">
                            <img class="ui tiny image center-block" src="{{ Products::getImage($product, 160, 160) }}" alt="{{ Products::getImage($product, 160, 160) }}"/>
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
                            @if(count($product->formats) > 0)
                                @foreach($product->formats as $format)
                                    <button class="ui icon btn btn-two buybutton"
                                            data-product="{{ $product->id . '-' . $product->formats[0]->id }}"
                                            data-price="{{ $product->formats[0]->price }}"
                                            data-thumbnail="{{ Products::getImage($product, 60, 60, "fit") }}"
                                            data-thumbnail_lg="{{ Products::getImage($product, 70, 110, "fit") }}"
                                            data-name="{{ $product->localization->name }}"
                                            data-quantity="1"
                                            data-description="{{ $product->localization->short_description }}"
                                            data-link="{{ route('product', ['slug' => $product->slug]) }}"
                                            >

                                        <p class="ui sub header gray">{{ $format->name }}</p>

                                        <i class="icon shop"></i>
                                        $ {{ number_format((float)$format->price, 2, '.', '') }}

                                    </button>


                                @endforeach
                            @endif

                            @if(!is_null($product->rating))
                                <span class="right floated" style="padding-top: 7.5px">
                                        @lang("boukem.rating")
                                    <div class="ui star rating" data-rating="4"></div>
                                </span>
                            @endif

                        </div>
                        {{-- extra content --}}

                    </div>
                    {{-- card --}}

                @endforeach
            </div>
            {{-- four cards --}}

        </div>
        {{-- sixteen wide column --}}

    </div>

    <br/>
    <br/>
</section>