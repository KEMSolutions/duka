@extends("app")

@section("content")
    @foreach($sites as $site)
        {{-- TODO FRANCIS HERE !!!!!
             Temporary fix : check if the sections rebates, featured, mixed have an array of products.
                If they do, @include them
                If not, skip them.
        --}}
        @if(isset($layoutData[$site]["products"]) || $site === "headline")
            @include("site.homepage._" . $site)
        @endif
    @endforeach
@stop

@section("scripts")
    <script>
        $(".indicator-down:first").hide();
        $(".section-title:first").hide();
    </script>
@endsection