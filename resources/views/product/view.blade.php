@extends("app")

@section("custom_css")
    <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
    <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
@endsection

@section("content")
    @include("product._breadcrumb", ["product" => $product])

    <section class="slice color-three column1_main_slice">
        <div class="w-section inverse">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">

                        {{-- Product description begins here. --}}
                        <section class="slice product-slice animate-hover-slide" itemscope itemtype="http://schema.org/Product">
                            <div class="w-section inverse blog-grid">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4 col-md-push-8">
                                            <div class="widget pricing-plans" id="product_info_box" data-product="{{ $product->id }}">
                                                <div class="w-box popular">

                                                    <img alt="" itemprop="image" src="//static.boutiquekem.com/productimg-8-300-300-{{ count($product->images) > 0 ? $product->images[0]->id : "0000"}}.{{ count($product->images) > 0 ? $product->images[0]->extension : "png"}}" class="img-responsive center-block hidden-sm hidden-md hidden-lg">

                                                    <h2 class="plan-title" itemprop="name">
                                                        {{ $product->localization->name}} -
                                                        <span id="product-format">{{ $product->formats[0]->name }}</span>
                                                    </h2>

                                                    {{-- TODO ... --}}
                                                    @if($product->formats[0]->discontinued)
                                                        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                                            <link itemprop="availability" href="http://schema.org/Discontinued">
                                                            <p class="text-center text-danger">
                                                                {{ Lang::get("boukem.product_unavailable") }}
                                                            </p>
                                                        </span>
                                                    @else
                                                        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">

                                                            {{-- TODO: update price with details from format --}}
                                                            <h3 class="price-tag color-one">
                                                                <meta itemprop="price" content="{{ $product->formats[0]->price }}">
                                                                <span itemprop="priceCurrency" content="CAD">$</span>
                                                                {{ number_format((float)$product->formats[0]->price, 2, '.', '') }}
                                                            </h3>

                                                            <ul>
                                                                <li> <i class="fa fa-fw"><img src="https://cdn.kem.guru/boukem/spirit/flags/CA.png" width="17" alt="CA"></i>
                                                                    {{ Lang::get("boukem.world_shipping") }}
                                                                </li>

                                                                {{-- TODO: update price with details from format --}}
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


                                                    <p class="plan-info" id="product_short_description" itemprop="description">{{ str_limit(strip_tags($product->localization->short_description), 200, "...") }}</p>

                                                    {{-- TODO ... --}}
                                                    @if(!$product->formats[0]->discontinued)
                                                        <p class="plan-select text-center">
                                                            <div class="input-qty-detail text-center">
                                                                <div class="form-group">
                                                                    <div class="input-group bootstrap-touchspin horizontal-align" style=""><span class="input-group-addon bootstrap-touchspin-prefix"></span><input type="text" class="form-control input-qty text-center" id="item_quantity" value="1"><span class="input-group-addon bootstrap-touchspin-postfix"></span></div>
                                                                </div>


                                                                @if(count($product->formats) > 1)
                                                                    <div class="ui buttons huge">

                                                                        @foreach($product->formats as $index => $format)

                                                                        {{-- Select format buttons (contains html5 data tags to be synced with the buybutton
                                                                             located after
                                                                        --}}
                                                                        <button class="ui button format-selection
                                                                        {{--@if ($index == 0)--}}
                                                                            {{--{!! 'active' !!}--}}
                                                                        {{--@endif--}}
                                                                                "
                                                                                data-product="{{ $format->id }}"
                                                                                data-price="{{ $format->price }}"
                                                                                data-thumbnail="{{ Products::thumbnail($product) }}"
                                                                                data-thumbnail_lg="{{ Products::thumbnailLg($product) }}"
                                                                                data-name="{{ $product->localization->name . " - " . $format->name }}"
                                                                                data-format="{{ $format->name }}"
                                                                                data-inventory-count="{{ $format->inventory->count }}"
                                                                                data-quantity="1"
                                                                                data-link="{{ route('product', ['slug' => $product->slug]) }}"
                                                                                >

                                                                            @if(count($product->formats) > 1)
                                                                                <p class="ui sub header">{{ $format->name }}</p>
                                                                            @endif
                                                                        </button>

                                                                        @if($format != end($product->formats))
                                                                            <div class="or" data-text=@lang("boukem.or")></div>
                                                                        @endif

                                                                    @endforeach
                                                                    </div>
                                                                @endif

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
                                                                            <i class="fa fa-check-circle"></i>
                                                                            @lang("boukem.add_cart")
                                                                        </div>
                                                                    </button>
                                                                 </div>
                                                            </div>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--VIDEOS--}}
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


                                            <div class="widget">
                                                <h4 class="widget-heading">{{ Lang::get("boukem.share") }}</h4>

                                                <ul class="categories highlight">

                                                    <li class="facebook_share_button">
                                                        {{-- TODO: INTEGREATE REAL LINKS FOR SHARING OPTIONS--}}
                                                        <a href="https://www.facebook.com/sharer/sharer.php?u=http://dev.boutiquekem.com/en/prod/en-probiotic-plus-120-capsules.html">
                                                            <span class="fa fa-facebook fa-fw"></span> {{ Lang::get("boukem.share_fb") }}
                                                        </a>
                                                    </li>

                                                    {{-- TODO: ICI AUSSI!--}}
                                                    <li class="pinterest_share_button"><a href="http://www.pinterest.com/pin/create/button/?url=http://dev.boutiquekem.com/en/prod/en-probiotic-plus-120-capsules.html&amp;media=//static.boutiquekem.com/productimg-8-700-700-83.jpg&amp;description=PROBIOTIC+Plus+-+120+capsules%0ARelief+from+urinary+tract+infections+%28UTIs%29Control+candida+yeast+infectionsStops+burning+during+urinationProtects+against+the+effects+of+antibioticsReduces+candida+during+antibiotic+therapyStops+bacteria+from+sticking+to+the+bladder+wallReduces+the+risk+of+recurring+bladder+infectionsPreviously+called+URIsmart" data-pin-do="buttonPin" data-pin-config="above">
                                                            <span class="fa fa-pinterest fa-fw"></span> {{ Lang::get("boukem.share_pin") }}
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>



                                            <div class="widget tags-wr">
                                                <h4 class="widget-heading">{{ Lang::get("boukem.categories") }}</h4>
                                                <ul class="tags-list">

                                                    {{-- TOOD: INTEGRATE THE RIGHT CATEGORIES--}}

                                                    <li>
                                                        <i class="fa fa-tags"></i>
                                                        <a href="/en/cat/fr-probiotiques.html">Probiotiques</a>
                                                    </li>

                                                    <li>
                                                        <i class="fa fa-tags"></i>
                                                        <a href="/en/cat/fr-vaginites.html">Vaginites</a>
                                                    </li>

                                                    <li>
                                                        <i class="fa fa-tags"></i>
                                                        <a href="/en/cat/fr-systeme-urinaire.html">Système urinaire</a>
                                                    </li>

                                                    <li>
                                                        <i class="fa fa-tags"></i>
                                                        <a href="/en/cat/en-lorna-vanderhaeghe.html">Lorna Vanderhaeghe</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div> <!-- Right column -->

                                        <div class="col-md-8 col-md-pull-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="w-box blog-post">
                                                        <figure>
                                                            <img alt="" itemprop="image" src="{{ Products::mainImage($product->id) }}" class="img-responsive center-block hidden-xs">

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

                                                        </figure>
                                                    </div>



                                                </div>
                                            </div>

                                        </div><!-- Left column -->


                                    </div>

                                </div>
                            </div>

                        </section>
                        {{-- Product description ends here --}}
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
