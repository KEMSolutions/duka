<section class="slice home_featured" style="background: white; color: black">
    @if ($showTab)
        @include("site.homepage._tab", ["tabTitle" => $layoutData["featured"]["tabTitle"]])
    @endif

    <div class="w-section inverse blog-grid" style="margin-top: -36px">
        <div class="container full-width">
            <div class="row grid-layout">

                {{--Include the appropriate layout (dense or regular cards) according to the layoutDense boolean--}}
                @if($layoutData["featured"]["layoutDense"])
                    @include("product.layout._product_card_dense",
                    [
                        "products" => array_slice($layoutData["featured"]["products"], 0, $layoutData["featured"]["limit"]),
                        "border" => true
                    ])
                @elseif(!$layoutData["featured"]["layoutDense"])
                    @include("product.layout._product_card",
                    [
                        "products" => array_slice($layoutData["featured"]["products"], 0, $layoutData["featured"]["limit"])
                    ])
                @endif

            </div>
        </div>
    </div>



</section>