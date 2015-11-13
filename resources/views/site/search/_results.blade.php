<div class="ui stackable grid layout-toggle-container grid-layout">
    @if (isset($results->tags) && count($results->tags))
        <div class="ui container grid vertically padded">
            <div class="row">
                <div class="column" style="padding-top: 2rem">
                    <h3 class="ui header">@lang("boukem.handpick")</h3>
                </div>
            </div>

            @include(
            'product.layout._product_card_dense', [
            'showTag' => false,
            'locale' => Localization::getCurrentLocale(),
            'products' => $results->tags[0]->products
        ])

        </div>
        <hr style="width: 100%"/>
    @endif


    <div class="ui container grid vertically padded">
        <div class="row">
            <div class="column" style="padding-top: 2rem">
                <h3 class="ui header">@lang('boukem.results_for', ["displayed" => $displayed, "total" => $total, "term" => $query])</h3>
            </div>
        </div>
    </div>


    @include(
    'product.layout._product_card_dense', [
        'showTag' => false,
        'locale' => Localization::getCurrentLocale(),
        'border' => false,
        'products' => $results->organic_results
])
    <div class="row" style="text-align: center; display: block">
        {!! $paginator->render() !!}
    </div>

</div>

