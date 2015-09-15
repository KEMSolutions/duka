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

            <div class="ui text container {{ $layoutData["headline"]["style"] }} headline-section-text">
                <h1 class="ui header centered aligned">{{ $layoutData["headline"]["title"] }}</h1>
                <h3 class="ui header centered aligned">{{ $layoutData["headline"]["subtitle"] }}</h3>
            </div>

        </div>
    </div>
</section>
