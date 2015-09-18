@extends("app")

@section("content")
    {{-- Include header --}}
    @include("site.wishlist._header")

    {{-- Include results --}}
    @include('site.wishlist._results')
@endsection

