{{-- Set products thumbnails. --}}
<?php
foreach($layoutData[ "featured" ]["products"] as $product) {
    if($product != null) {
        $product->images[0]->thumbnail_lg = Utilities::setImageSizeAndMode(70, 110, "fit", $product->images[0]->url);
        $product->images[0]->thumbnail = Utilities::setImageSizeAndMode(60, 60, "fit", $product->images[0]->url);
        $product->images[0]->img_featured = Utilities::setImageSizeAndMode(80, 120, "fit", $product->images[0]->url);
        $product->images[0]->img_featured_lg = Utilities::setImageSizeAndMode(160, 160, "fit", $product->images[0]->url);
    }
}
?>

<section class="slice home_featured" style="background: white; color: black">
    @if ($showTab)
        @include("site.homepage._tab", ["tabTitle" => $layoutData["featured"]["tabTitle"]])
    @endif

    <div class="w-section inverse blog-grid" style="margin-top: -36px">
        <div class="container full-width">
            <div class="row">

                {{--Include the appropriate layout (dense or regular cards) according to the layoutDense boolean--}}
                @if($layoutData["featured"]["layoutDense"])
                    @include("product.layout._product_card_dense",
                    [
                        "products" => array_slice($layoutData["featured"]["products"], 0, $layoutData["featured"]["limit"]),
                        "border" => false
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