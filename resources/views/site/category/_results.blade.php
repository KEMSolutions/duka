<div class="col-md-10">
    @include(
        'product.layout._product_card_dense', [
            'showTag' => false,
            'locale' => Localization::getCurrentLocale(),
            'products' => $products,
            'border' => false
    ])
</div>


{{--Pagination --}}
<div class="row text-center">
    {!! $paginator->render() !!}
</div>