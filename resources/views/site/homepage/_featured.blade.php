{{-- SECTION: FEATURED --}}


<section class="featured-section">

    @if ($showTab)
        @include("site.homepage._tab", ["tabTitle" => $layoutData["featured"]["tabTitle"]])
    @endif


            {{--Include the appropriate layout (dense or regular cards) according to the layoutDense boolean--}}
            @if(!$layoutData["featured"]["layoutDense"])
                @include("product.layout._product_card_dense",
                [
                    "products" => array_slice($layoutData["featured"]["products"], 0, $layoutData["featured"]["limit"]),
                    "border" => true
                ])
            @elseif($layoutData["featured"]["layoutDense"])
                @include("product.layout._product_card",
                [
                    "products" => array_slice($layoutData["featured"]["products"], 0, $layoutData["featured"]["limit"])
                ])
            @endif


</section>