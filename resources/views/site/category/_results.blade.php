<div class="row layout-toggle-container grid-layout little-padded">
    <div class="ui grid">
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
