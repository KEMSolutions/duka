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


    <div class="ui container equal width grid vertically padded">
        <div class="row">
            <div class="column" style="padding-top: 2rem">
                <h3 class="ui header">@lang('boukem.results_for', ["term" => $query])</h3>
            </div>
        </div>

        <div class="full-width">
            <div class="ui four stackable doubling link cards">
                @foreach($results->organic_results as $product)
                    {!! view("product._card", ["product"=>$product])->render() !!}
                @endforeach
            </div>
        </div>
    </div>

    <div class="row" style="text-align: center; display: block">
        {!! $paginator->render() !!}
    </div>

</div>

