<section id="homepageCarousel" class="carousel carousel-1 home_headline slide {{ $color }}">
    @if ($showTab)
        @include("site._tab", ["pageTitle" => $pageTitle])
    @endif

    <div class="carousel-inner">
        <div class="item item-{{ $style }} active"
                @if ( isset($backgroundUrl))
                    style="background-image:url({{$backgroundUrl}})"
                @endif
                >

            <div class="description fluid-center">
                <span class="title">TITRE</span>
                <span class="subtitle">Sous-Titre</span>
            </div>
        </div>
    </div>
</section>