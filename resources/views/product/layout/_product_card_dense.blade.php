<div class="ui stackable grid grid-layout grid-layout-dense">

@foreach($products as $product)
    @if (is_object($product) && isset($product->id))
        <div class="four wide column text-center dense-product
            @if (!$border)
             {!! 'no-border' !!}
            @endif
            ">

            {{--
                Favorite heart icon

                TODO: Assume that favoriting a product has only 1 format. If there are multiple formats, it will favorite it BUT the persistance will fail.

            --}}
            @if(count($product->formats) != 0)
                <span class="pull-right favorite-wrapper popup"
                      data-product="{{$product->id . '-' . $product->formats[0]->id }}"
                      data-description="{{ $product->localization->short_description }}"
                      title='@lang("boukem.wishlist_add")'
                      data-variation="tiny">
                    <i class="circular icon heart favorite-heart"></i>
                </span>
            @endif

            {{-- Product Image --}}
            <a href="{{ route('product', ['slug' => $product->slug]) }}" class="strong">
                <img src="{{ Products::getImage($product, 160, 160) }}"
                     class="product-image center-block"
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

            {{-- Product name --}}
            <div class="name">
                <a href="{{ route('product', ['slug' => $product->slug]) }}" class="ui tiny header dark">
                    {{ $product->localization->name }}
                </a>
            </div>


            {{-- Product short description. --}}
            <div class="short-description hidden">
                <p>{{ $product->localization->short_description }}</p>
            </div>


            {{-- Buybutton --}}
            <div class="price">
                @if(count($product->formats) > 0)
                    @foreach($product->formats as $format)
                        <button class="ui icon btn btn-two buybutton"
                                data-product="{{ $product->id . '-' . $format->id }}"
                                data-price="{{ $format->price }}"
                                data-thumbnail="{{ Products::getImage($product, 60, 60, "fit") }}"
                                data-thumbnail_lg="{{ Products::getImage($product, 70, 110, "fit") }}"
                                data-name="{{ $product->localization->name . " - " . $format->name }}"
                                data-quantity="1"
                                data-description="{{ $product->localization->short_description }}"
                                data-link="{{ route('product', ['slug' => $product->slug]) }}"
                                >

                                <p class="ui sub header gray">{{ $format->name }}</p>

                            <i class="icon shop"></i>
                            $ {{ number_format((float)$format->price, 2, '.', '') }}
                        </button>
                    @endforeach
                @endif
            </div>
        </div>

    @endif

@endforeach

</div>