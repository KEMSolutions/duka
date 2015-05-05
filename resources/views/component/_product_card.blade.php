@foreach($products as $product)
      {{--TODO: TEMPORARY FIX AS TO WHY THE $products array contains empty fields?--}}
{{--      {{ var_dump($product) }}--}}
    @if(is_object($product))
        <div class="item col-lg-3 col-md-4 col-sm-6">
            <div class="w-box">
                <figure>
                    <a href="/{{ $locale }}/prod/{{ $product->slug }}">
                        <img src="//static.boutiquekem.com/productimg-300-280-{{ $product->images[0]->id . "." . $product->images[0]->extension }}" class="img-responsive center-block hidden-xs hidden-sm"/>
                        <img src="//static.boutiquekem.com/productimg-70-600-560-{{ $product->images[0]->id . "." . $product->images[0]->extension }}" class="img-responsive center-block visible-xs-block visible-sm-block"/>
                    </a>
                    <span class="date-over"><strong><a href="{{ $locale }}/fr/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a></strong></span>
                    <h2><a href="/{{ $locale }}/prod/{{ $product->slug }}">{{ $product->localization->name }}</a></h2>

                    <p>
                        Soulagement des infections des voies urinaires (IVU)
                        Contrôle des infections à levures
                        Mets un terme aux brûlures dur...
                    </p>


                <span class="w-footer">
                    <div class="pull-left"><strong class="pricetag">{{ $product->price }} $</strong></div>
					<button class="btn btn-success pull-right buybutton" data-product="577" data-abid="v"><i class="fa fa-shopping-cart"></i> {{ Lang::get("boukem.buy") }}</button>
                    <span class="clearfix"></span>

                </span>
                </figure>
            </div>
        </div>

    @endif
@endforeach


