@foreach($products as $product)
    @if(is_object($product) && isset($product->brand->slug))
        <div class="item col-lg-3 col-md-4 col-sm-6">
            <div class="w-box">
                <figure>

                    <a href="{{ route('product', ['slug' => $product->slug]) }}" class="strong">
                        <img src="//static.boutiquekem.com/productimg-300-280-{{ $product->images[0]->id . "." . $product->images[0]->extension }}" class="img-responsive center-block "/>
                    </a>

                    <span class="date-over">
                        <strong>
                            <a class="color-two" href="{{ route('product', ['slug' => $product->slug]) }}">
                                {{ $product->brand->name }}
                            </a>
                        </strong>
                    </span>

                    <h2>
                        <a href="{{ route('product', ['slug' => $product->slug]) }}" class="color-two">
                            {{ $product->localization->name }}
                        </a>
                    </h2>

                    <p>
                        {{ str_limit(strip_tags($product->localization->short_description), 100, "...") }}
                    </p>


                <span class="w-footer">
                    <div class="pull-left"><strong class="pricetag">$ {{ number_format((float)$product->formats[0]->price, 2, '.', '') }}</strong></div>
					<button class="btn btn-success pull-right buybutton"
                            data-product="{{ $product->id }}"
                            data-price="{{ $product->formats[0]->price }}"
                            data-thumbnail="{{ Products::thumbnail($product->id) }}"
                            data-thumbnail_lg="{{ Products::thumbnailLg($product->id) }}"
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
