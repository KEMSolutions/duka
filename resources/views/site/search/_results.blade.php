<div class="ui container grid vertically padded">
    <div class="row">
        <div class="column">
            <h3 class="ui header">@lang('boukem.results_for', ["displayed" => $displayed, "total" => $total, "term" => $query])</h3>
        </div>
    </div>
</div>

<div class="ui stackable grid">
    <div class="row little-padded">
        <div class="ui stackable grid layout-toggle-container grid-layout">
            @if (isset($results->tags) && count($results->tags))
                <h3>Tags</h3>
                @include(
                    'product.layout._product_card_dense', [
                    'showTag' => false,
                    'locale' => Localization::getCurrentLocale(),
                    'products' => $results->tags[0]->products
                ])
            @endif


            @include(
            'product.layout._product_card_dense', [
                'showTag' => false,
                'locale' => Localization::getCurrentLocale(),
                'border' => false,
                'products' => $results->organic_results
        ])
        </div>
    </div>

    <div class="row" style="text-align: center; display: block">
        {!! $paginator->render() !!}
    </div>
</div>