<div class="row little-padded">
    <div class="ui stackable grid layout-toggle-container grid-layout">
        @include(
        'product.layout._product_card_dense', [
            'showTag' => false,
            'locale' => Localization::getCurrentLocale(),
            'border' => false
    ])
    </div>

</div>


{{--Pagination --}}
<div class="row text-center">
    @if ($paginator)
        {!! $paginator->render() !!}
    @endif
</div>
