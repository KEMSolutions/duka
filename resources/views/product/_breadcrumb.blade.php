<div class="ui container grid vertically padded">
    <div class="two column row">
        <div class="right floated column">
            <div class="ui breadcrumb pull-right">

                {{-- Some products are without brands. --}}
                @if(count($product->brand))
                    <a class="section" href="/{{ $locale }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a>
                @endif

                <i class="right chevron icon divider"></i>
                <a class="active section">{{ $product->localization->name }}</a>
            </div>
        </div>
    </div>
</div>