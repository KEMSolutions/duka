@foreach($products as $product)
      {{--TODO: TEMPORARY FIX AS TO WHY THE $products array contains empty fields?--}}
      {{--{{ dd($products) }}--}}
    @if(is_object($product))
        <div class="item col-lg-3 col-md-4 col-sm-6">
            <div class="w-box">
                <figure>
                    <a href="/{{ $locale }}/prod/{{ $product->slug }}">
                        <img src="//static.boutiquekem.com/productimg-300-280-{{ $product->images[0]->id . "." . $product->images[0]->extension }}" class="img-responsive center-block hidden-xs hidden-sm"/>
                        <img src="//static.boutiquekem.com/productimg-70-600-560-{{ $product->images[0]->id . "." . $product->images[0]->extension }}" class="img-responsive center-block visible-xs-block visible-sm-block"/>
                    </a>
                    <span class="date-over"><strong><a href="{{ $locale }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a></strong></span>
                    <h2><a href="/{{ $locale }}/prod/{{ $product->slug }}">{{ $product->localization->name }}</a></h2>

                    <p>
                        {{ str_limit(strip_tags($product->localization->short_description), 100, "...") }}
                    </p>


                <span class="w-footer">
                    <div class="pull-left"><strong class="pricetag">{{ $product->price }} $</strong></div>
					<button class="btn btn-success pull-right buybutton"
                            data-product="{{ $product->id }}"
                            data-price="{{ $product->price }}"
                            data-thumbnail="//static.boutiquekem.com/productimg-50-50-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                            data-thumbnail_lg="//static.boutiquekem.com/productimg-120-160-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                            data-name="{{ $product->localization->name }}">
                        ><i class="fa fa-shopping-cart"></i> {{ Lang::get("boukem.buy") }}</button>
                    <span class="clearfix"></span>

                </span>
                </figure>
            </div>
        </div>

    @endif
@endforeach


