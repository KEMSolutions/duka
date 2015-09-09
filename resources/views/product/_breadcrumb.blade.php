<div class="ui container grid vertically padded">
    <div class="two column row">
        <div class="left floated column">
            <h1 class="ui header">{{ $product->localization->name }}</h1>
        </div>

        <div class="right floated column">
            <div class="ui breadcrumb pull-right">
                <a class="section" href="/{{ $locale }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a>
                <i class="right chevron icon divider"></i>
                <a class="active section">{{ $product->localization->name }}</a>
            </div>
        </div>
    </div>
</div>