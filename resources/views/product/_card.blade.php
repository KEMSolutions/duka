<product-card
        name="{{ $product->localization->name }}"
        :product-id="{{ $product->id }}"
        route="{{ route('product', ['slug' => $product->slug]) }}"
        :format-number="{{ count($product->formats) }}"
        image="{{ Products::getImage($product->id, 350, 350, 'fit') }}"
        thumbnail="{{ Products::getImage($product->id, 60, 60, 'fit') }}"
        thumbnail-lg="{{ Products::getImage($product->id, 70, 110, 'fit') }}"
        description="{{ (strlen($product->localization->short_description) > 200) ? substr($product->localization->short_description,0,200).'…' : $product->localization->short_description }}"
        :products="{{ json_encode($product->formats) }}"
        :first-format-price="{{ $product->formats[0]->price }}"

        @if (count($product->formats) > 0 && $product->formats[0]->reduced_price)
                :first-format-reduced-price="{{ $product->formats[0]->reduced_price->price }}"
                first-format-rebate-percent="{{ App\ApiObjects\Products::formatRebatePercent($product->formats[0]) }} %"
        @endif

        @if (isset($product->brand->slug))
                brand-slug="{{ route('brand',  ['slug' => $product->brand->slug]) }}"
                brand-name="{{ $product->brand->name }}"
        @endif
        >

        {{-- SEO PURPOSE --}}
        <div class="content">
                <div class="header">
                        <a href="{{ route('product', ['slug' => $product->slug]) }}">
                                {{ $product->localization->name }}
                        </a>
                </div>

                @if (isset($product->brand->slug))
                        <div class="meta">
                                <a href="{{ route('brand',  ['slug' => $product->brand->slug]) }}">{{ $product->brand->name }}</a>
                        </div>
                @endif


                <div class="description">
                        {{ (strlen($product->localization->short_description) > 200) ? substr($product->localization->short_description,0,200).'…' : $product->localization->short_description }}
                </div>
        </div>

</product-card>



