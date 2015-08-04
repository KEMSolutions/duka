<div class="col-md-10 layout-toggle-container grid-layout">
    @include(
        'product.layout._product_card_dense', [
            'showTag' => false,
            'locale' => Localization::getCurrentLocale(),
            'border' => false
    ])
</div>


{{--Pagination --}}
<div class="row text-center">
    {!! $paginator->render() !!}
</div>