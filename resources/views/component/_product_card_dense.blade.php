@foreach($products as $product)
    {{--TODO: TEMPORARY FIX AS TO WHY THE $products array contains empty fields?--}}
    {{--      {{ var_dump($product) }}--}}
    @if(is_object($product) && isset($product->brand->slug))
        <div class="col-xs-6 col-sm-4 col-md-3 text-center dense_product">

            {{--TODO: SET $product->slug instead of ID when facade is ready. --}}
            <a href="/{{ $locale }}/dev/prod/{{ $product->id }}" class="strong">
                <img src="//static.boutiquekem.com/productimg-160-160-{{ $product->images[0]->id . "-h." . $product->images[0]->extension }}" class="img-responsive center-block hidden-xs hidden-sm"/>
            </a>

            {{--<a href="/{{ $locale }}/prod/{{ $product->slug }}">--}}
                {{--<img src="//static.boutiquekem.com/productimg-160-160-{{ $product->images[0]->id . "-h." . $product->images[0]->extension }}" class="img-responsive center-block hidden-xs hidden-sm"/>--}}
            {{--</a>--}}

            <div class="text-uppercase brand">
                <strong><a href="/{{ $locale }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a></strong>
            </div>

            <div class="name">
                <a href="/{{ $locale }}/dev/prod/{{ $product->slug }}">{{ $product->localization->name }}</a>
            </div>

            <div class="price">
                <button class="btn btn-default btn-xs buybutton"
                        data-product="{{ $product->id }}"
                        data-price="{{ $product->price }}"
                        data-thumbnail="//static.boutiquekem.com/productimg-50-50-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                        data-thumbnail_lg="//static.boutiquekem.com/productimg-120-160-{{ $product->images[0]->id . "." . $product->images[0]->extension }}"
                        data-name="{{ $product->localization->name }}">
                    <i class="fa fa-shopping-cart"></i>
                    $ {{ $product->price }}
                </button>
            </div>
        </div>

    @endif
@endforeach


