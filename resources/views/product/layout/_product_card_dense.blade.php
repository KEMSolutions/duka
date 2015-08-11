
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

            {{-- <a href="/{{ $locale }}/prod/{{ $product->slug }}" class="strong"> --}}
            <a href="{{ route('product', ['slug' => $product->slug]) }}" class="strong">
                <img src="{{ Products::imgFeaturedLg($product->id) }}"
                    class="product-image img-responsive center-block"
                    alt="{{ $product->localization->name }}" />
            </a>

            {{-- Link to product brand --}}
            @if (isset($product->brand->slug))
                <div class="text-uppercase brand">
                    {{-- <strong><a href="/{{ $locale }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a></strong> --}}
                    <strong>
                        <a href="{{ route('brand', ['slug' => $product->brand->slug]) }}">
                            {{ $product->brand->name }}
                        </a>
                    </strong>
                </div>
            @endif

            <div class="name">
                {{-- <a href="/{{ $locale }}/prod/{{ $product->slug }}">{{ $product->localization->name }}</a> --}}
                <a href="{{ route('product', ['slug' => $product->slug]) }}">
                    {{ $product->localization->name }}
                </a>
            </div>

            <div class="short-description hidden">
                <p>{{ $product->localization->short_description }}</p>
            </div>

            <div class="price">
                <button class="btn btn-two btn-sm buybutton"
                        data-product="{{ $product->id }}"
                        data-price="{{ $product->formats[0]->price }}"
                        data-thumbnail="{{ Products::thumbnail($product->id) }}"
                        data-thumbnail_lg="{{ Products::thumbnailLg($product->id) }}"
                        data-name="{{ $product->localization->name }}"
                        data-quantity="1"
                        {{-- data-link="prod/{{ $product->slug }}"> --}}
                        data-link="{{ route('product', ['slug' => $product->slug]) }}">
                    <i class="fa fa-shopping-cart"></i>
                    $ {{ number_format((float)$product->formats[0]->price, 2, '.', '') }}
                </button>
            </div>
        </div>

    @endif

@endforeach

