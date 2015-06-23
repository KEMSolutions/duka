
<div class="w-section inverse blog-grid">
    <div class="container">
        <div class="row">

            <h1>Search for products</h1>
            <h3>Results for <i>{{ $query }}</i></h3>

            {{-- Search results --}}
            @include(
                'product.layout._product_card_dense', [
                    'showTag' => false,
                    'locale' => Localization::getCurrentLocale(),
                    'products' => $results
            ])
        </div>

        {{-- Pagination --}}
        <div class="row text-center">
            {!! $paginator->render() !!}
        </div>
    </div>
</div>