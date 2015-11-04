<div class="ui stackable grid grid-layout grid-layout-regular">

    @foreach($products as $product)
        @if(is_object($product) && isset($product->brand->slug))
            <div class="four wide column dense-product">
                <div class="w-box">
                    <figure>

                        <a href="{{ route('product', ['slug' => $product->slug]) }}" class="strong">
                            <img src="//static.boutiquekem.com/productimg-300-280-{{ $product->images[0]->id . "." . $product->images[0]->extension }}" class="img-responsive center-block "/>
                        </a>

                        <h2>
                            <a href="{{ route('product', ['slug' => $product->slug]) }}" class="darker">
                                {{ $product->localization->name }}
                            </a>
                        </h2>

                        <h5>
                            <a href="{{ route('product', ['slug' => $product->slug]) }}" class="dark">
                                {{ $product->brand->name }}
                            </a>
                        </h5>

                        <p>
                            {{ str_limit(strip_tags($product->localization->short_description), 100, "...") }}
                        </p>


                        <span class="w-footer">
                            <div class="pull-left">
                                <strong class="pricetag">
                                    $ {{ number_format((float)$product->formats[0]->price, 2, '.', '') }}
                                </strong>
                            </div>

                            @if(count($product->formats) > 0)
                                @foreach($product->formats as $format)
                                    <button class="ui button btn-one right floated buybutton"
                                            data-product="{{ $product->id . '-' . $format->id }}"
                                            data-price="{{ $format->price }}"
                                            data-thumbnail="{{ Products::getImage($product, 60, 60, "fit") }}"
                                            data-thumbnail_lg="{{ Products::getImage($product, 70, 110, "fit") }}"
                                            data-name="{{ $product->localization->name . " - " . $format->name }}"
                                            data-quantity="1"
                                            data-description="{{ $product->localization->short_description }}"
                                            data-link="{{ route('product', ['slug' => $product->slug]) }}"
                                            >
                                        <i class="icon shop"></i>
                                        {{ Lang::get("boukem.buy") }}
                                    </button>
                                @endforeach
                            @endif
                            <span class="clearfix"></span>

                        </span>
                    </figure>
                </div>
            </div>

        @endif
    @endforeach
</div>
