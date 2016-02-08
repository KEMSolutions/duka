<div class="card">
    <a class="ui fluid image" href="{{ route('product', ['slug' => $product->slug]) }}">
        @if (count($product->formats) > 0 && $product->formats[0]->reduced_price)
      <div class="ui orange ribbon label">
        - {{ App\ApiObjects\Products::formatRebatePercent($product->formats[0]) }} %
      </div>
        @endif

        <img src="{{ Products::getImage($product->id, 350, 350, 'fit') }}">
    </a>
    <div class="content">
        <div class="header"><a href="{{ route('product', ['slug' => $product->slug]) }}">{{ $product->localization->name }}</a></div>
        <div class="meta">
            @if (isset($product->brand->slug))
                <a href="{{ route('brand', ['slug' => $product->brand->slug]) }}">{{ $product->brand->name }}</a>
            @endif

        </div>
        <div class="description">
            {{ (strlen($product->localization->short_description) > 200) ? substr($product->localization->short_description,0,200).'…' : $product->localization->short_description }}
        </div>
    </div>

    <div class="extra content">
        @foreach ($product->formats as $format)
            <div class="ui right floated labeled button buybutton
                        @if ($format->discontinued)
                        disabled
                        @endif"
                 data-product="{{ $product->id . '-' . $format->id }}"
                 data-price="{{ isset($format->reduced_price) ? $format->reduced_price->price : $format->price }}"
                 data-thumbnail="{{ Products::getImage($product->id, 60, 60, "fit") }}"
                 data-thumbnail_lg="{{ Products::getImage($product->id, 70, 110, "fit") }}"
                 data-name="{{ $product->localization->name . " - " . $format->name }}"
                 data-quantity="1"
                 data-description="{{ $product->localization->short_description }}"
                 data-link="{{ route('product', ['slug' => $product->slug]) }}">
                <div class="ui red button" style="background-color: #{{ Store::info()->colors->color_two }}!important">
                    <i class="add to cart icon"></i>
                    {{ $format->name ? $format->name : "1" }}
                </div>
                <div class="ui basic red left pointing label" style="border-color: #{{ Store::info()->colors->color_two }}!important;color: #{{ Store::info()->colors->color_two }}!important;">
                    {{
                    isset($format->reduced_price) ?
                        money_format('%n', $format->reduced_price->price) :
                        money_format('%n', $format->price)
                    }}
                </div>
            </div>

        @endforeach
    </div>
</div>