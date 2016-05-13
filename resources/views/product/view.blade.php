@extends("app")

@section('title')
    {{ $product->localization->name }}
@endsection

@section('custom_metas')
    @foreach ($product->localization->alt as $localization)
        <link rel="alternate" hreflang="{{ $localization->locale->language }}" href="/{{ $localization->locale->language }}/prod/{{ $localization->slug }}.html" />
    @endforeach
    {{-- Facebook open graph --}}
    <meta property="og:url" content="{{ route('product', ["slug"=>$product->localization->slug]) }}" />
    <meta property="og:type" content="product" />
    <meta property="og:title" content="{{ $product->localization->name }}" />
    <meta property="og:description" content="{{ $product->localization->short_description }}" />
    <meta property="og:image" content="{{ Products::getImage($product->id, 1000, 1000) }}" />
@endsection

@section("content")
    @include("product._breadcrumb", ["product" => $product])



    <div class="ui container stackable grid" itemscope itemtype="http://schema.org/Product">
        <div class="ui row">
            <div class="ten wide center aligned column">

                {{-- Product Image. --}}
                <div class="ui medium image">
                    <img src="{{ Products::getImage($product->id, 500, 500) }}"
                         alt="{{ $product->localization->name }}"
                         itemprop="image"/>
                </div>
            </div>

            <div class="six wide column">
                <div class="ui basic segment">

                    {{--Product name and price. --}}
                    <h1 class="ui header" itemprop="name">
                        {{ $product->localization->name }}

                        <div class="sub header">
                            <span id="product-format-name">
                                {{ $product->formats[0]->name }}
                            </span>
                            -

                        @if(isset($product->formats[0]->reduced_price))
                                <span class="text-strikethrough">
                                    CAD ${{ number_format((float)$product->formats[0]->price, 2, '.', '') }}
                                </span>
                                <span id="product-price" class="strong text-danger">
                                    CAD ${{ number_format((float)$product->formats[0]->reduced_price->price, 2, '.', '') }}

                                    @if (Utilities::currencyCodeForUser() && Utilities::currencyCodeForUser() !== "CAD")
                                        ( {{ Utilities::currencyCodeForUser() }}
                                        {{ number_format((float)$product->formats[0]->price * Utilities::getAlternateCurrencyRate(Utilities::currencyCodeForUser()), 2, '.', '') }} )
                                    @endif
                                </span>
                            @else
                                <span id="product-price" class="strong">
                                    CAD ${{ number_format((float)$product->formats[0]->price, 2, '.', '') }}

                                    @if (Utilities::currencyCodeForUser() && Utilities::currencyCodeForUser() !== "CAD")
                                        ( {{ Utilities::currencyCodeForUser() }}
                                        {{ number_format((float)$product->formats[0]->price * Utilities::getAlternateCurrencyRate(Utilities::currencyCodeForUser()), 2, '.', '') }} )
                                    @endif
                                </span>
                            @endif
                        </div>
                    </h1>

                    <div class="content">
                        {{--Short description. --}}
                        <p id="product_short_description"
                           itemprop="description">
                            {{ str_limit(strip_tags($product->localization->short_description), 500, "...") }}
                        </p>

                        <div class="ui divider"></div>

                        @if($product->formats[0]->discontinued)
                            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <span class="invisible" itemprop="price">
                                    CAD ${{ number_format((float)$product->formats[0]->price, 2, '.', '') }}
                                </span>
                                <link itemprop="availability" href="http://schema.org/Discontinued">
                                <p class="text-center text-danger">
                                    {{ Lang::get("boukem.product_unavailable") }}
                                </p>
                            </span>
                        @else
                            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <span class="hidden" itemprop="price">
                                    CAD ${{ number_format((float)$product->formats[0]->price, 2, '.', '') }}
                                </span>

                                {{-- Product Format. --}}
                                @if(count($product->formats) > 1)
                                    <div>
                                        <p class="inline-block pull-left">Format</p>

                                        <div class="inline block pull-right">
                                            <select name="product-format" id="product-format">
                                                @foreach($product->formats as $index => $format)
                                                    <option value="{{ $product->id . '-' . $format->id }}"
                                                            data-format="{{ $format->name }}"
                                                            data-price="{{ $format->price }}"
                                                            data-reduced="{{ isset($format->reduced_price->price) ? number_format($format->reduced_price->price, 2, '.', '') : "undef" }}"
                                                            data-name="{{ $product->localization->name . " - " . $format->name }}">
                                                        @if(isset($format->reduced_price->price))
                                                            {{ $format->name . " -  CAD $ " . number_format($format->reduced_price->price, 2, '.', '') }}
                                                        @else
                                                            {{ $format->name . " -  CAD $ " . number_format($format->price, 2, '.', '') }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ui divider float-clear"></div>
                                @endif

                                <div style="padding-bottom: 20px">
                                    <p class="inline-block pull-left">@lang("boukem.quantity")</p>

                                    <div class="inline-block pull-right">
                                        <button class="qty-selector" data-action="remove">-</button>
                                        <input type="text"
                                               class="qty-selector-input text-center"
                                               value="1"
                                               onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                >
                                        <button class="qty-selector" data-action="add">+</button>
                                    </div>
                                </div>

                                <div class="ui divider float-clear"></div>

                                {{-- buybutton takes by default the value of the first format --}}
                                @if(isset($product->formats[0]->reduced_price))
                                    <button class="ui button color-one fluid big buybutton"
                                            data-product="{{ $product->id . '-' . $product->formats[0]->id }}"
                                            data-price="{{ $product->formats[0]->reduced_price->price }}"
                                            data-thumbnail="{{ Products::getImage($product, 60, 60, "fit") }}"
                                            data-thumbnail_lg="{{ Products::getImage($product, 70, 110, "fit") }}"
                                            data-name="{{ $product->localization->name . " - " . $product->formats[0]->name }}"
                                            data-format="{{ $product->formats[0]->name }}"
                                            data-inventory-count="{{ $product->formats[0]->inventory->count }}"
                                            data-quantity="1"
                                            data-link="{{ route('product', ['slug' => $product->slug]) }}">
                                        @lang("boukem.add_cart")
                                    </button>
                                @else
                                    <button class="ui button color-one fluid big buybutton"
                                            data-product="{{ $product->id . '-' . $product->formats[0]->id }}"
                                            data-price="{{ $product->formats[0]->price }}"
                                            data-thumbnail="{{ Products::getImage($product, 60, 60, "fit") }}"
                                            data-thumbnail_lg="{{ Products::getImage($product, 70, 110, "fit") }}"
                                            data-name="{{ $product->localization->name . " - " . $product->formats[0]->name }}"
                                            data-format="{{ $product->formats[0]->name }}"
                                            data-inventory-count="{{ $product->formats[0]->inventory->count }}"
                                            data-quantity="1"
                                            data-link="{{ route('product', ['slug' => $product->slug]) }}">
                                        @lang("boukem.add_cart")
                                    </button>
                                @endif

                                {{-- Favorite feature is disabled for now. --}}
                                {{--<button class="btn btn-link center-block favorite-link"--}}
                                {{--data-product="{{$product->id . '-' . $product->formats[0]->id }}"--}}
                                {{--data-description="{{ $product->localization->short_description }}"--}}
                                {{--title='@lang("boukem.wishlist_add")'>--}}
                                {{--<i class="icon heart"></i>--}}
                                {{--Add to wishlist!--}}
                                {{--</button>--}}

                                <div class="ui divider"></div>


                                {{-- Various informative shipping messages. --}}
                                <div class="ui relaxed list">

                                    <div class="item">
                                        @if (in_array($country_code, $supported_countries))
                                            <i class="{{ strtolower($country_code) }} flag"></i>
                                            {{ Lang::get("boukem.world_shipping") }}
                                        @else
                                            <i class="ca flag"></i>
                                            {{ Lang::get("boukem.canadian_shipping") }}
                                        @endif
                                    </div>


                                    <div id="inventory-count" data-country-code="{{ $country_code }}">
                                        @if($product->formats[0]->inventory->count > 5)
                                            <link itemprop="availability" href="http://schema.org/LimitedAvailability">
                                            <div class="item text-success">
                                                <i class="{{ ($country_code === "US" || $country_code === "CA") ? "shipping" : "plane" }} icon"></i>
                                                {{ Lang::get("boukem.express_shipping") }}
                                            </div>
                                        @elseif($product->formats[0]->inventory->count > 0)
                                            <link itemprop="availability" href="http://schema.org/InStock" >
                                            <div class="item text-warning">
                                                <i class="{{ ($country_code === "US" || $country_code === "CA") ? "shipping" : "plane" }} icon"></i>
                                                {{ Lang::get("boukem.stock_left", array("quantity" => $product->formats[0]->inventory->count)) }}
                                            </div>
                                        @else
                                            <link itemprop="availability" href="http://schema.org/LimitedAvailability" >
                                            <div class="item">
                                                <i class="{{ ($country_code === "US" || $country_code === "CA") ? "shipping" : "plane" }} icon"></i>
                                                {{ Lang::get("boukem.shipping_time") }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="ui row">
            <div class="ui padded grid">
                <div class="ui accordion">

                    {{-- Long description. --}}
                    @if(isset($product->localization->long_description))
                        <div class="active title">
                            <h3 class="ui header">
                                <i class="dropdown icon"></i>
                                @lang("boukem.product_details")
                            </h3>
                        </div>

                        <div class="active content">
                            <span>{!! $product->localization->long_description !!}</span>
                            <div class="ui divider"></div>
                        </div>

                    @endif


                    {{-- We'll eventually put the content section here (list of ingredients) --}}


                    {{-- Usage section. --}}
                    @if(isset($product->localization->usage))
                        <div class="title">
                            <h3 class="ui header">
                                <i class="dropdown icon"></i>
                                @lang("boukem.product_usage")
                            </h3>
                        </div>

                        <div class="content">
                            <span>{!! $product->localization->usage !!}</span>
                            <div class="ui divider"></div>
                        </div>
                    @endif


                    {{-- Warning section. --}}
                    @if(isset($product->localization->warning))
                        <div class="title">
                            <h3 class="ui red header">
                                <i class="dropdown icon"></i>
                                @lang("boukem.product_warning")
                            </h3>
                        </div>

                        <div class="content">
                            <span>{!! $product->localization->warning !!}</span>
                            <div class="ui divider"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        {{-- Brands / BarCode / Misc. information. --}}
        <div class="ui row text centered">
            <div class="ui list">
                <div class="item">
                    <span>{{ Lang::get("boukem.CUP/EAN") }}</span>
                    <span class="bold" itemprop="gtin13">{{ isset($product->formats[0]->barcode) ? $product->formats[0]->barcode : "—" }}</span>
                </div>

                {{--Some products are without brands. --}}
                @if(count($product->brand))
                    <div class="item" itemprop="brand" itemscope="" itemtype="http://schema.org/Brand">
                        <span>{{ Lang::get("boukem.brand") }}</span>
                        <span class="bold" itemprop="name">{{ $product->brand->name }}</span>
                    </div>
                @endif
            </div>
        </div>


        <div class="ui row">
            <div class="row sixteen wide column">

                {{--Videos. --}}
                @if(isset($product->videos) && count($product->videos) > 0)
                @section("custom_css")
                    <link rel="stylesheet" href="//vjs.zencdn.net/4.8/video-js.css"/>
                @endsection

                @section("scripts")
                    <script src="//vjs.zencdn.net/4.8/video.js"></script>
                    <script>
                        //store_video
                        document.createElement('video');
                        document.createElement('audio');
                        document.createElement('track');
                    </script>
                @endsection

                @include("product._product_video", ["videos" => $product->videos ])
                @endif

                <?php /*  Reviews.
            <div class="ui comments full-width invisible">
                <h3 class="ui dividing header">Comments</h3>
                <div class="comment">
                    <a class="avatar">
                        <img src="http://lorempicsum.com/simpsons/50/50/1">
                    </a>
                    <div class="content">
                        <a class="author">Matt</a>
                        <div class="metadata">
                            <span class="date">Today at 5:42PM</span>
                        </div>
                        <div class="text">
                            How artistic!
                        </div>
                        <div class="actions">
                            <a class="reply">Reply</a>
                        </div>
                    </div>
                </div>
                <div class="comment">
                    <a class="avatar">
                        <img src="http://lorempicsum.com/simpsons/50/50/2">
                    </a>
                    <div class="content">
                        <a class="author">Elliot Fu</a>
                        <div class="metadata">
                            <span class="date">Yesterday at 12:30AM</span>
                        </div>
                        <div class="text">
                            <p>This has been very useful for my research. Thanks as well!</p>
                        </div>
                        <div class="actions">
                            <a class="reply">Reply</a>
                        </div>
                    </div>
                </div>
                <div class="comment">
                    <a class="avatar">
                        <img src="http://lorempicsum.com/simpsons/50/50/3">
                    </a>
                    <div class="content">
                        <a class="author">Jenny Hess</a>
                        <div class="metadata">
                            <span class="date">Just now</span>
                        </div>
                        <div class="text">
                            Elliot you are always so right :)
                        </div>
                        <div class="actions">
                            <a class="reply">Reply</a>
                        </div>
                    </div>
                </div>
                <div class="comment">
                    <a class="avatar">
                        <img src="http://lorempicsum.com/simpsons/50/50/4">
                    </a>
                    <div class="content">
                        <a class="author">Joe Henderson</a>
                        <div class="metadata">
                            <span class="date">5 days ago</span>
                        </div>
                        <div class="text">
                            Dude, this is awesome. Thanks so much
                        </div>
                        <div class="actions">
                            <a class="reply">Reply</a>
                        </div>
                    </div>
                </div>
                <form class="ui reply form">
                    <div class="field">
                        <textarea></textarea>
                    </div>
                    <div class="ui blue labeled submit icon button">
                        <i class="icon edit"></i> Add Reply
                    </div>
                </form>
            </div>
             End of reviews.
            */ ?>
            </div>
            {{--End of third row. --}}
        </div>
    </div>

@endsection
