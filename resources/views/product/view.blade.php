@extends("app")

@section("custom_css")
    <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
    <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
@endsection

@section("content")
    @include("product._breadcrumb", ["product" => $product])

    <div class="ui container stackable grid" itemscope itemtype="http://schema.org/Product">

        {{-- Firt row includes product description, pricing plans, sharing and categories --}}
        <div class="row">

            {{-- Left column. --}}
            <div class="ten wide column" id="product-description" data-product="{{ $product->id }}">

                <div class="ui raised very padded text container segment">

                    {{-- Product image. --}}
                    <img src="{{ Products::mainImage($product->id) }}" alt="{{ $product->localization->name }}" class="center-block" itemprop="image"/>

                    <br/>

                    {{-- Product name and description. --}}
                    <div id="product_long_description">
                        <span>{!! $product->localization->long_description !!}</span>
                        <ul class="meta-list text-center">

                            <li>
                                <span>{{ Lang::get("boukem.CUP/EAN") }}</span>
                                <span class="bold" itemprop="gtin13">{{ isset($product->formats[0]->barcode) }}</span>
                            </li>

                            <li itemprop="brand" itemscope="" itemtype="http://schema.org/Brand">
                                <span>{{ Lang::get("boukem.brand") }}</span>
                                <span class="bold" itemprop="name">{{ $product->brand->name }}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            {{-- End of left column. --}}

            {{-- Right column. --}}
            <div class="five wide column" id="product-info-box">
                <div class="ui raised text segment pricing-plans">
                    {{-- TODO: Show an image for smaller device (mobile) --}}

                    {{-- Product name. --}}
                    <h1 class="ui header text-center" itemprop="name">
                        {{ $product->localization->name}} -
                        <span id="product-format">{{ $product->formats[0]->name }}</span>
                    </h1>

                    {{-- Product price. --}}
                    @if($product->formats[0]->discontinued)
                        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                            <link itemprop="availability" href="http://schema.org/Discontinued">
                            <p class="text-center text-danger">
                                {{ Lang::get("boukem.product_unavailable") }}
                            </p>
                        </span>
                    @else
                        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                            <h3 class="price-tag color-one">
                                <meta itemprop="price" content="{{ $product->formats[0]->price }}">
                                <span itemprop="priceCurrency" content="CAD">$</span>
                                {{ number_format((float)$product->formats[0]->price, 2, '.', '') }}
                            </h3>

                            <ul>
                                <li> <i class="fa fa-fw"><img src="https://cdn.kem.guru/boukem/spirit/flags/CA.png" width="17" alt="CA"></i>
                                    {{ Lang::get("boukem.world_shipping") }}
                                </li>

                                <div id="inventory-count" data-country-code="{{ $country_code }}">
                                    @if($product->formats[0]->inventory->count > 5)
                                        <link itemprop="availability" href="http://schema.org/LimitedAvailability">
                                        <li class="text-success"><i class="fa {{ ($country_code === "US" || $country_code === "CA") ? "fa-truck" : "fa-plane" }} fa-fw"></i> {{ Lang::get("boukem.express_shipping") }}</li>
                                    @elseif($product->formats[0]->inventory->count > 0)
                                        <link itemprop="availability" href="http://schema.org/InStock" >
                                        <li class="text-warning"><i class="fa {{ ($country_code === "US" || $country_code === "CA") ? "fa-truck" : "fa-plane" }} fa-fw"></i> {{ Lang::get("boukem.stock_left", array("quantity" => $product->formats[0]->inventory->count)) }}</li>
                                    @else
                                        <link itemprop="availability" href="http://schema.org/LimitedAvailability" >
                                        <li><i class="fa {{ ($country_code === "US" || $country_code === "CA") ? "fa-truck" : "fa-plane" }} fa-fw"></i> {{ Lang::get("boukem.shipping_time") }}</li>
                                    @endif
                                </div>

                                <li><i class="fa fa-lock fa-fw"></i>{{ Lang::get("boukem.secure_payment") }}</li>

                            </ul>

                        </span>
                    @endif

                    {{-- Short description. --}}
                    <p class="plan-info" id="product_short_description" itemprop="description">{{ str_limit(strip_tags($product->localization->short_description), 200, "...") }}</p>

                    {{-- Quantity inputs. --}}
                    @if(!$product->formats[0]->discontinued)
                        <div class="input-qty-detail text-center">

                            {{-- We have to let the .form-group class for the product format feature to work (as of now) --}}
                            <div class="form-group">
                                <div class="input-group horizontal-align" style="">
                                    <span class="input-group-addon bootstrap-touchspin-prefix"></span>
                                    <input type="text" class="form-control input-qty text-center" id="item_quantity" value="1">
                                    <span class="input-group-addon bootstrap-touchspin-postfix"></span>
                                </div>
                            </div>

                            {{-- If product has more than 1 format --}}
                            @if(count($product->formats) > 1)
                                <div class="ui buttons">

                                    @foreach($product->formats as $index => $format)

                                        {{-- Select format buttons (contains html5 data tags to be synced with the buybutton
                                             located after
                                        --}}
                                        <button class="ui btn btn-three format-selection
                                            @if ($index == 0)
                                                {!! 'active' !!}
                                            @endif
                                                "
                                                data-product="{{ $format->id }}"
                                                data-price="{{ $format->price }}"
                                                data-thumbnail="{{ Products::thumbnail($product) }}"
                                                data-thumbnail_lg="{{ Products::thumbnailLg($product) }}"
                                                data-name="{{ $product->localization->name . " - " . $format->name }}"
                                                data-format="{{ $format->name }}"
                                                data-inventory-count="{{ $format->inventory->count }}"
                                                data-link="{{ route('product', ['slug' => $product->slug]) }}"
                                                >

                                            @if(count($product->formats) > 1)
                                                <p class="ui sub header white">{{ $format->name }}</p>
                                            @endif
                                        </button>

                                        @if($format != end($product->formats))
                                            <div class="or" data-text=@lang("boukem.or")></div>
                                        @endif

                                    @endforeach
                                </div>
                            @endif
                            {{-- End of multiple formats product. --}}

                            <br/>
                            <br/>

                            {{-- buybutton takes by default the value of the first format --}}
                            <div class="buybutton-format-selection-wrapper">
                                <button
                                        class="btn btn-three buybutton horizontal-align"
                                        data-product="{{ $product->formats[0]->id }}"
                                        data-price="{{ $product->formats[0]->price }}"
                                        data-thumbnail="{{ Products::thumbnail($product) }}"
                                        data-thumbnail_lg="{{ Products::thumbnailLg($product) }}"
                                        data-name="{{ $product->localization->name . " - " . $product->formats[0]->name }}"
                                        data-format="{{ $product->formats[0]->name }}"
                                        data-inventory-count="{{ $product->formats[0]->inventory->count }}"
                                        data-quantity="1"
                                        data-link="{{ route('product', ['slug' => $product->slug]) }}"
                                ">
                                <div class="add-cart">
                                    <i class="check circle outline icon"></i>
                                    @lang("boukem.add_cart")
                                </div>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sharing options. --}}
                <div class="ui basic segment">
                    <h4 class="ui header">{{ Lang::get("boukem.share") }}</h4>

                    <ul class="categories highlight">

                        <li class="facebook_share_button">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ Store::info()->url }}/{{ Localization::getCurrentLocale() }}/prod/{{ $product->slug }}" >
                            <i class="facebook icon"></i> {{ Lang::get("boukem.share_fb") }}
                            </a>
                        </li>

                        <li class="pinterest_share_button"><a href="http://www.pinterest.com/pin/create/button/?url={{ Store::info()->url }}/{{ Localization::getCurrentLocale() }}/prod/{{ $product->slug }}&amp;media={{ $product->images[0]->thumbnail }}&amp;description={{ $product->localization->name }}-{{ $product->localization->short_description }}" data-pin-do="buttonPin" data-pin-config="above">
                                <i class="pinterest icon"></i> {{ Lang::get("boukem.share_pin") }}
                            </a>
                        </li>
                    </ul>
                </div>
                {{-- End of sharing options. --}}


                {{-- Categories related to the product. --}}
                <div class="ui basic segment">
                    <h4 class="ui header">{{ Lang::get("boukem.categories") }}</h4>
                    <ul class="tags-list">

                        {{-- TOOD: INTEGRATE THE RIGHT CATEGORIES--}}

                        <li>
                            <i class="tags icon"></i>
                            <a href="{{ Store::info()->url }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a>
                        </li>
                    </ul>
                </div>
                {{-- End of related categories. --}}

            </div>
        </div>
        {{-- End of first row. --}}

        {{-- Second row includes videos, reviews --}}
        <div class="row sixteen wide column">

            {{-- Videos. --}}
            @if(count($product->videos) > 0)
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

            {{-- Reviews. --}}
            <div class="ui comments full-width">
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
            {{-- End of reviews. --}}

        </div>
        {{-- End of second row. --}}

    </div>

@endsection
