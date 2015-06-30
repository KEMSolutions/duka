<div class="container-fluid category-header" style="background-image: url({{ str_replace("{width}/{height}", "1500/200", $presentation->background->image) }})">
    <div class="center-block text-center category-header-text">
        {{--logo--}}
        <img class="category-logo" src="{{ $presentation->logo->image }}" alt="{{ $name }}"/>

        {{--title--}}
        <h1 class="category-title">{{ $presentation->title }}</h1>

        {{--subtitle--}}
        <h4 class="category-subtitle">{{ $presentation->subtitle }}</h4>
    </div>
</div>