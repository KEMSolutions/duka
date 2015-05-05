<section class="slice color-one home_mixed">
    @if ($showTab)
        @include("site._tab", ["tabTitle" => $layoutData["mixed"]["tabTitle"]])
    @endif

    <div class="w-section inverse">
        <div class="container">
            <div class="row">
                <div class="col-md-8">

                    @foreach($layoutData["mixed"]["products"] as $product)
                        <div class="col-xs-12">
                            <div class="aside-feature">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="img-feature">
                                            <a href="/{{ $locale }}/prod/{{ $product->slug }}">
                                                <img src="//static.boutiquekem.com/productimg-120-120-{{ $product->images[0]->id . "." . $product->images[0]->extension }}" class="img-thumbnail center-block"/>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <p>
                                            <a href="/{{ $locale }}/prod/{{ $product->slug }}" class="strong">
                                                {{ $product->localization->name }}
                                            </a>
                                                <span class="pull-right">
                                                    <strong><i class="fa fa-star"></i> {{ Lang::get("boukem.featured") }}</strong>
                                                </span>
                                        </p>

                                        <p>
                                            {{ str_limit(strip_tags($product->localization->short_description), 100, "...") }}

                                            <br/>
                                            <button class="btn btn-one btn-xs buybutton"
                                                    data-product="{{ $product->id }}"
                                                    data-price="{{ $product->price }}"
                                                    data-thumbnail="//static.boutiquekem.com/productimg-50-50-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                                                    data-thumbnail_lg="//static.boutiquekem.com/productimg-120-160-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                                                    data-name="{{ $product->localization->name }}">
                                                <i class="fa fa-shopping-cart"></i>{{ $product->price }} $
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div> <!-- col-md-8 -->

                <div class="col-md-4">
                    <div class="widget">
                        <form class="form-inline" method="get" action="/">
                            <div class="input-group col-xs-12">
                                <input type="search" class="form-control" name="q" placeholder="{{ Lang::get("boukem.search") }}" value="" autocomplete="off" spellcheck="false">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit"><span class="fa fa-search"><span class="sr-only">{{ Lang::get("boukem.search") }}</span></span></button>
                                        </span>
                            </div>
                        </form>
                    </div>

                    <div class="widget">
                        <h4 class="widget-heading">{{ Lang::get("boukem.shortcuts") }}</h4>
                    </div>

                    <ul class="categories highlight">
                        {{-- TODO : Nothing for now ...--}}
                    </ul>

                </div> <!-- col-md-4 -->

            </div><!-- row -->

        </div> <!-- container -->
    </div> <!-- w-section inverse -->


</section>