<div class="card">
    <div class="image">
        <img src="{{ Products::getImage($product->id, 350, 350, 'fit') }}">
    </div>
    <div class="content">
        <div class="header"><a href="{{ route('product', ['slug' => $product->slug]) }}">{{ $product->localization->name }}</a></div>
        <div class="meta">
            @if (isset($product->brand->slug))
                <a href="{{ route('brand', ['slug' => $product->brand->slug]) }}">{{ $product->brand->name }}</a>
            @endif

        </div>
        <div class="description">
            {{ $product->localization->short_description }}
        </div>
    </div>

    <div class="extra content">
        @foreach ($product->formats as $format)

            <div class="ui large label text-center center-block btn btn-one-inverted buybutton"
                 style="padding:1rem; outline:none"
                 tabindex="0"
                 data-product="{{ $product->id . '-' . $format->id }}"
                 data-price="{{ isset($format->reduced_price) ? $format->reduced_price->price : $format->price }}"
                 data-thumbnail="{{ Products::getImage($product->id, 60, 60, "fit") }}"
                 data-thumbnail_lg="{{ Products::getImage($product->id, 70, 110, "fit") }}"
                 data-name="{{ $product->localization->name . " - " . $format->name }}"
                 data-quantity="1"
                 data-description="{{ $product->localization->short_description }}"
                 data-link="{{ route('product', ['slug' => $product->slug]) }}">
                <i class="add to cart icon"></i>
                {{$format->name}}
                <a class="detail dark">$
                    {{
                        isset($format->reduced_price) ?
                            number_format((float)$format->reduced_price->price, 2, '.', '') :
                            number_format((float)$format->price, 2, '.', '')
                    }}
                </a>
            </div>

        @endforeach
    </div>
</div>