<div class="container-fluid category-header no-padding" style="background-image: url('{{ $background }}')">
    <div class="category-overlay">
        <div class="center-block text-center category-header-text">
            {{--logo--}}
            <img class="category-logo" src="{{ $presentation->logo->image }}" alt="{{ $name }}"/>

            {{--title--}}
            <h1 class="category-title">{{ $presentation->title }}</h1>

            {{--subtitle--}}
            <h3 class="category-subtitle">{{ $presentation->subtitle }}</h3>
        </div>
    </div>
</div>