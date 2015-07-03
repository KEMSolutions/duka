<?php foreach($products as $product): ?>

    {{-- Performance check --}}
    <?php
    if (empty($product)) {
        continue;
    }
    ?>

    @if(is_object($product))
        <div class="col-xs-6 col-sm-4 col-md-3 text-center dense_product
                @if ($border)
                    {!! 'no-border' !!}
                @endif
        ">

            <a href="/{{ $locale }}/prod/{{ $product->slug }}" class="strong">
                <img src="//static.boutiquekem.com/productimg-160-160-{{ count($product->images) > 0 ? $product->images[0]->id . "-h." . $product->images[0]->extension : "0000.png" }}" class="img-responsive center-block"/>
            </a>

            {{-- Link to product brand --}}
            @if (isset($product->brand->slug))
                <div class="text-uppercase brand">
                    <strong><a href="/{{ $locale }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a></strong>
                </div>
            @endif

            <div class="name">
                <a href="/{{ $locale }}/prod/{{ $product->slug }}">{{ $product->localization->name }}</a>
            </div>

            <div class="price">
                <button class="btn btn-default btn-xs buybutton"
                        data-product="{{ $product->id }}"
                        data-price="{{ $product->price }}"
                        data-thumbnail="//static.boutiquekem.com/productimg-50-50-{{ count($product->images) > 0 ? $product->images[0]->id . "." . $product->images[0]->extension : "0000.png" }}"
                        data-thumbnail_lg="//static.boutiquekem.com/productimg-70-110-{{ count($product->images) > 0 ? $product->images[0]->id . "." . $product->images[0]->extension : "0000.png" }}"
                        data-name="{{ $product->localization->name }}"
                        data-quantity="1">
                    <i class="fa fa-shopping-cart"></i>
                    $ {{ number_format((float)$product->price, 2, '.', '') }}
                </button>
            </div>
        </div>

    @endif

<?php endforeach; ?>


