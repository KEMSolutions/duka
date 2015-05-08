<div class="pg-opt pin">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>{{ $product->localization->name }}</h2>
            </div>
            <div class="col-md-6">

                <ol class="breadcrumb">
                    <li><a href="/{{ $locale }}/cat/{{ $product->brand->slug }}">{{ $product->brand->name }}</a></li>
                    <li class="active">{{ $product->localization->name }}</li></ol>
            </div>
        </div>
    </div>
</div>

