@extends("app")

@section("content")
    @forelse($sites as $site)
        {{-- TODO FRANCIS HERE !!!!!
             Temporary fix : check if the sections rebates, featured, mixed have an array of products.
                If they do, @include them
                If not, skip them.
        --}}
        @if(isset($layoutData[$site]["products"]) || $site === "headline")
            @include("site.homepage._" . $site)
        @endif
    @empty

    <div class="ui vertical padded stripe segment">

        <div class="ui four stackable doubling link cards">
            @foreach(Products::random(4) as $product)
                {!! view("product._card", ["product"=>$product])->render() !!}
            @endforeach
        </div>
</div>

    @endforelse
@stop