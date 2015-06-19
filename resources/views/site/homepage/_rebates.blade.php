<section class="slice home_rebates" style="background: white; color: black">
    @if ($showTab)
        @include("site.homepage._tab", ["tabTitle" => $layoutData["rebates"]["tabTitle"]])
    @endif

    <div class="w-section inverse blog-grid">
        <div class="container">
            <div class="row">

                {{--Include the appropriate layout (dense or regular cards) according to the layoutDense boolean--}}
                @if($layoutData["rebates"]["layoutDense"])
                    @include("product.layout._product_card_dense",
                    ["products" => array_slice($layoutData["rebates"]["products"], 0, $layoutData["rebates"]["limit"])])
                @elseif(!$layoutData["rebates"]["layoutDense"])
                    @include("product.layout._product_card",
                    ["products" => array_slice($layoutData["rebates"]["products"], 0, $layoutData["rebates"]["limit"])])
                @endif

            </div>
        </div>
    </div>



</section>