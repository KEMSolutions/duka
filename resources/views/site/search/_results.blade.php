
<div class="w-section inverse blog-grid">
    <div class="container">
        <div class="row">

            <h1>Search for products</h1>
            <h3>Results for <i>{{ $query }}</i></h3>

            {{-- Suggested results (tags) --}}
            {{-- Hey @Rob, the tags are in $results->tags --}}
            @if (count($results->tags))
                <h3>Testing tags...</h3>
                @include(
                    'product.layout._product_card_dense', [
                        'showTag' => false,
                        'locale' => Localization::getCurrentLocale(),
                        'products' => $results->tags[0]->products
                ])
            @endif

            {{-- Search results --}}
            <h3>Testing organic results...</h3>
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