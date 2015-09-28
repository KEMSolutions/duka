
<div class="w-section inverse blog-grid">
    <div class="container">
        <div class="row">

            <h1>@lang('boukem.search')</h1>
            <h3>@lang('boukem.results_for', ["term" => $query])</h3>

            {{-- Suggested results (tags) --}}
            {{-- Hey @Rob, the tags are in $results->tags --}}
            @if (isset($results->tags) && count($results->tags))
                <h3>Tags</h3>
                @include(
                    'product.layout._product_card_dense', [
                        'showTag' => false,
                        'locale' => Localization::getCurrentLocale(),
                        'products' => $results->tags[0]->products
                ])
            @endif

            {{-- Search results --}}
            @include(
                'product.layout._product_card_dense', [
                    'showTag' => false,
                    'locale' => Localization::getCurrentLocale(),
                    'products' => $results->organic_results
            ])
        </div>

        {{-- Pagination --}}
        <div class="row text-center">
            {!! $paginator->render() !!}
        </div>
    </div>
</div>