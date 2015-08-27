{{-- SECTION: HEADLINE --}}

<section class="headline-section">
    <div class="ui grid">
        <div class="sixteen wide column headline-section-background"
        @if ( isset($layoutData["headline"]["backgroundUrl"]))
             style="background-image:url({{$layoutData["headline"]["backgroundUrl"]}})"
                @endif
                >


            @if ($showTab)
                @include("site.homepage._tab", ["tabTitle" => $layoutData["headline"]["tabTitle"]])
            @endif

            <div class="description description-{{ $layoutData["headline"]["style"] }} fluid-center">
                <span class="title">{{ $layoutData["headline"]["title"] }}</span>
                <span class="subtitle">{{ $layoutData["headline"]["subtitle"] }}</span>
            </div>

        </div>
    </div>
</section>
