<section id="homepageCarousel" class="carousel carousel-1 home_headline slide {{ $color }}">
    @if ($showTab)
        @include("site._tab", ["tabTitle" => $layoutData["headline"]["tabTitle"]])
    @endif

    <div class="carousel-inner">
        <div class="item item-{{ $layoutData["headline"]["style"] }} active"
                @if ( isset($layoutData["headline"]["backgroundUrl"]))
                    style="background-image:url({{$layoutData["headline"]["backgroundUrl"]}})"
                @endif
                >

            <div class="description fluid-center">
                <span class="title">{{ $layoutData["headline"]["title"] }}</span>
                <span class="subtitle">{{ $layoutData["headline"]["subtitle"] }}</span>
            </div>
        </div>
    </div>
</section>