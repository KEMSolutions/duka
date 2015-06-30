@foreach($products as $product)
      {{--TODO: TEMPORARY FIX AS TO WHY THE $products array contains empty fields?--}}
      {{--{{ dd($products) }}--}}
    @if(is_object($product) && isset($product->brand->slug))
        <div class="item col-lg-3 col-md-4 col-sm-6">
            <div class="w-box">
                <figure>

                    <a href="/{{ $locale }}/prod/{{ $product->slug }}" class="strong">
                        <img src="//static.boutiquekem.com/productimg-300-280-{{ $product->images[0]->id . "." . $product->images[0]->extension }}" class="img-responsive center-block "/>
                    </a>

                    <span class="date-over">
                        <strong>
                            <a class="color-two" href="{{ $locale }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a>
                        </strong>
                    </span>

                    <h2><a href="/{{ $locale }}/prod/{{ $product->slug }}" class="color-two">{{ $product->localization->name }}</a></h2>

                    <p>
                        {{ str_limit(strip_tags($product->localization->short_description), 100, "...") }}
                    </p>


                <span class="w-footer">
                    <div class="pull-left"><strong class="pricetag">$ {{ number_format((float)$product->price, 2, '.', '') }}</strong></div>
					<button class="btn btn-success pull-right buybutton"
                            data-product="{{ $product->id }}"
                            data-price="{{ $product->price }}"
                            data-thumbnail="//static.boutiquekem.com/productimg-50-50-{{ count($product->images) > 0 ? $product->images[0]->id . "." . $product->images[0]->extension : "0000.png" }}"
                            data-thumbnail_lg="//static.boutiquekem.com/productimg-70-110-{{ count($product->images) > 0 ? $product->images[0]->id . "." . $product->images[0]->extension : "0000.png" }}"
                            data-name="{{ $product->localization->name }}"
                            data-quantity="1">
                        <i class="fa fa-shopping-cart"></i> {{ Lang::get("boukem.buy") }}</button>
                    <span class="clearfix"></span>

                </span>
                </figure>
            </div>
        </div>

    @endif
@endforeach


