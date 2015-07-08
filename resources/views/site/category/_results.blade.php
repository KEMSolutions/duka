<div class="w-section inverse blog-grid">
    <div class="container">

        @if(count($featured))
            <div class="row" id="featured-wrapper">
                @include("site.category._featured", [
                    "featured" => $featured,
                    "locale" => Localization::getCurrentLocale()
                ])
            </div>
        @endif

        <hr/>

        <div class="row">

            {{--TODO: Implement breadcrumbs--}}
            {{--TODO: search functions (refining results)--}}

            @include("site.category._searchbar")

            <div class="col-md-10">
                @include(
                    'product.layout._product_card_dense', [
                        'showTag' => false,
                        'locale' => Localization::getCurrentLocale(),
                        'products' => $products,
                        'border' => "true"
                ])
            </div>
        </div>

        {{--Pagination --}}
        <div class="row text-center">
            {!! $paginator->render() !!}
        </div>
    </div>
</div>