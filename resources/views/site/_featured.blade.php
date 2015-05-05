<section class="slice home_featured" style="background: white; color: black">
    @if ($showTab)
        @include("site._tab", ["tabTitle" => $layoutData["featured"]["tabTitle"]])
    @endif

    <div class="w-section inverse blog-grid">
        <div class="container">
            <div class="row">

                {{--Include the appropriate layout (dense or regular cards) according to the layoutDense boolean--}}
                @if($layoutData["featured"]["layoutDense"])
                    @include("component._product_card_dense",
                    ["products" => array_slice($layoutData["featured"]["products"], 0, $layoutData["featured"]["limit"])])
                @elseif($layoutData["featured"]["layoutDense"])
                    @include("component._product_card",
                    ["products" => array_slice($layoutData["featured"]["products"], 0, $layoutData["featured"]["limit"])])
                @endif

            </div>
        </div>
    </div>



</section>