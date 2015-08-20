
@foreach($products as $product)

    @if (is_object($product) && isset($product->id))
        <div class="col-xs-6 col-sm-4 col-md-3 text-center dense_product
                @if (!$border)
                    {!! 'no-border' !!}
                @endif
                ">
            <span class="pull-right favorite-wrapper" data-product="{{ $product->id }}">
                <i class="fa fa-heart favorite-heart"></i>
            </span>

            <a href="{{ route('product', ['slug' => $product->slug]) }}" class="strong">
                <img src="{{ Products::imgFeaturedLg($product) }}"
                     class="product-image img-responsive center-block"
                     alt="{{ $product->localization->name }}" />
            </a>

            {{-- Link to product brand --}}
            @if (isset($product->brand->slug))
                <div class="text-uppercase brand">
                    <strong>
                        <a href="{{ route('brand', ['slug' => $product->brand->slug]) }}">
                            {{ $product->brand->name }}
                        </a>
                    </strong>
                </div>
            @endif

            <div class="name">
                <a href="{{ route('product', ['slug' => $product->slug]) }}">
                    {{ $product->localization->name }}
                </a>
            </div>

            <div class="short-description hidden">
                <p>{{ $product->localization->short_description }}</p>
            </div>

            <div class="price">

                @if(count($product->formats) != 0)
                    @foreach($product->formats as $format)
                        <button class="btn btn-two btn-sm buybutton"
                                data-product="{{ $format->id }}"
                                data-price="{{ $format->price }}"
                                data-thumbnail="{{ Products::thumbnail($product) }}"
                                data-thumbnail_lg="{{ Products::thumbnailLg($product) }}"
                                data-name="{{ $product->localization->name . " - " . $format->name }}"
                                data-quantity="1"
                                data-link="{{ route('product', ['slug' => $product->slug]) }}"
                                >

                            @if(count($product->formats) > 1)
                                <p class="ui sub header gray">{{ $format->name }}</p>
                            @endif

                            <i class="fa fa-shopping-cart"></i>
                            $ {{ number_format((float)$product->formats[0]->price, 2, '.', '') }}
                        </button>
                    @endforeach
                @endif
            </div>
        </div>

    @endif

@endforeach

