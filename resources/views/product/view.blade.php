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
                        <section class="slice product-slice animate-hover-slide" itemscope="" itemtype="http://schema.org/Product">
                            <div class="w-section inverse blog-grid">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-4 col-md-push-8">
                                            <div class="widget pricing-plans" id="product_info_box" data-product="{{ $product->id }}">
                                                <div class="w-box popular">

                                                    <img alt="" itemprop="image" src="//static.boutiquekem.com/productimg-8-300-300-{{ $product->images[0]->id }}.{{ $product->images[0]->extension }}" class="img-responsive center-block hidden-sm hidden-md hidden-lg">

                                                    <h2 class="plan-title" itemprop="name">
                                                        {{ $product->localization->name }}
                                                    </h2>

                                                    <span itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">

                                                        <h3 class="price-tag color-one">
                                                            <meta itemprop="price" content="{{ $product->price }}"><span itemprop="priceCurrency" content="CAD">$</span>{{ $product->price }}
                                                        </h3>

                                                        <ul>
                                                            <li> <i class="fa fa-fw"><img src="https://cdn.kem.guru/boukem/spirit/flags/CA.png" width="17" alt="CA"></i>
                                                                {{ \Illuminate\Support\Facades\Lang::get("boukem.world_shipping") }}
                                                            </li>

                                                            <link itemprop="availability" href="http://schema.org/LimitedAvailability">

                                                            <li><i class="fa fa-truck fa-fw"></i> {{ \Illuminate\Support\Facades\Lang::get("boukem.shipping_estimate") }}</li>

                                                            <li><i class="fa fa-lock fa-fw"></i>{{ \Illuminate\Support\Facades\Lang::get("boukem.secure_payment") }}</li>

                                                        </ul>
											</span>


                                                    <p class="plan-info" id="product_short_description" itemprop="description">{{ str_limit(strip_tags($product->localization->short_description), 200, "...") }}</p>
                                                    <p class="plan-select text-center">


                                                    </p><div class="input-qty-detail form-inline text-center">
                                                        <div class="form-group">
                                                            <div class="input-group bootstrap-touchspin" style=""><span class="input-group-btn"><button class="btn btn-default bootstrap-touchspin-down" type="button">-</button></span><span class="input-group-addon bootstrap-touchspin-prefix"></span><input type="text" class="form-control input-qty text-center" id="item_quantity" value="1"><span class="input-group-addon bootstrap-touchspin-postfix"></span><span class="input-group-btn"><button class="btn btn-default bootstrap-touchspin-up" type="button">+</button></span></div>
                                                        </div>

                                                        <button class="btn btn-three buybutton visible-lg-inline"
                                                                data-product="{{ $product->id }}"
                                                                data-price="{{ $product->price }}"
                                                                data-thumbnail="//static.boutiquekem.com/productimg-50-50-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                                                                data-thumbnail_lg="//static.boutiquekem.com/productimg-120-160-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                                                                data-name="{{ $product->localization->name }}">
                                                            ><i class="fa fa-check-circle"></i>  {{ \Illuminate\Support\Facades\Lang::get("boukem.buy") }}</button>
                                                        <button class="btn btn-block btn-three center-block buybutton hidden-lg" data-product="{{ $product->id }}"
                                                                data-price="{{ $product->price }}"
                                                                data-thumbnail="//static.boutiquekem.com/productimg-50-50-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                                                                data-thumbnail_lg="//static.boutiquekem.com/productimg-120-160-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                                                                data-name="{{ $product->localization->name }}">
                                                            ><i class="fa fa-check-circle"></i>  {{ \Illuminate\Support\Facades\Lang::get("boukem.buy") }}</button>
                                                    </div>
                                                    <br/>
                                                </div>
                                            </div>

                                            <div class="widget">
                                                <h4 class="widget-heading">{{ \Illuminate\Support\Facades\Lang::get("boukem.share") }}</h4>

                                                <ul class="categories highlight">

                                                    <li class="facebook_share_button">
                                                        {{-- TODO: INTEGREATE REAL LINKS FOR SHARING OPTIONS--}}
                                                        <a href="https://www.facebook.com/sharer/sharer.php?u=http://dev.boutiquekem.com/en/prod/en-probiotic-plus-120-capsules.html">
                                                            <span class="fa fa-facebook fa-fw"></span> {{ \Illuminate\Support\Facades\Lang::get("boukem.share_fb") }}
                                                        </a>
                                                    </li>

                                                    {{-- TODO: ICI AUSSI!--}}
                                                    <li class="pinterest_share_button"><a href="http://www.pinterest.com/pin/create/button/?url=http://dev.boutiquekem.com/en/prod/en-probiotic-plus-120-capsules.html&amp;media=//static.boutiquekem.com/productimg-8-700-700-83.jpg&amp;description=PROBIOTIC+Plus+-+120+capsules%0ARelief+from+urinary+tract+infections+%28UTIs%29Control+candida+yeast+infectionsStops+burning+during+urinationProtects+against+the+effects+of+antibioticsReduces+candida+during+antibiotic+therapyStops+bacteria+from+sticking+to+the+bladder+wallReduces+the+risk+of+recurring+bladder+infectionsPreviously+called+URIsmart" data-pin-do="buttonPin" data-pin-config="above">
                                                            <span class="fa fa-pinterest fa-fw"></span> {{ \Illuminate\Support\Facades\Lang::get("boukem.share_pin") }}
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>



                                            <div class="widget tags-wr">
                                                <h4 class="widget-heading">{{ \Illuminate\Support\Facades\Lang::get("boukem.categories") }}</h4>
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
                                                            <img alt="" itemprop="image" src="//static.boutiquekem.com/productimg-8-700-500-{{ $product->images[0]->id }}.{{ $product->images[0]->extension }}" class="img-responsive center-block hidden-xs">

                                                            <div id="product_long_description">
                                                                <span>{!! $product->localization->long_description !!}</span>
                                                                <ul class="meta-list text-center">

                                                                    <li>
                                                                        <span>{{ \Illuminate\Support\Facades\Lang::get("boukem.CUP/EAN") }}</span>
                                                                        <span class="bold" itemprop="gtin13">{{ isset($product->barcode) }}</span>
                                                                    </li>

                                                                    <li itemprop="brand" itemscope="" itemtype="http://schema.org/Brand">
                                                                        <span>{{ \Illuminate\Support\Facades\Lang::get("boukem.brand") }}</span>
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

@section("scripts")
    <script src="/js/cart-drawer.js"></script>
@endsection