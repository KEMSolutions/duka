<div class="w-section inverse blog-grid">
    <div class="container">
        <div class="row">

            {{--TODO: Featured products (limited to 3 on desktop, slide them on mobile)--}}
            {{--TODO: Blur the background of header (get the right dimension)--}}
            {{--TODO: Implement breadcrumbs--}}
            {{--TODO: search functions (refining results)--}}
            {{--TODO: Pagination--}}

            @include(
                'product.layout._product_card_dense', [
                    'showTag' => false,
                    'locale' => Localization::getCurrentLocale(),
                    'products' => $products
            ])
        </div>

         {{--Pagination --}}
        <div class="row text-center">
            {!! $paginator->render() !!}
        </div>
    </div>
</div>