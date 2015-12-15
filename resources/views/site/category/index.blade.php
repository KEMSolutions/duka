@extends("app")
@section('title')
{{ $name }}
@endsection

    @section("content")
        {{-- Include header --}}
        @include("site.category._header", ['name' => $name])

        {{-- Include breadcrumbs --}}
        @include('site.category._breadcrumbs')

            <div class="ui stackable grid">
                {{-- Include filter sidebar --}}
                @include('site.category._filter_sidebar', ['total' => $total])

                <div class="thirteen wide column">
                    {{--Include filter summary container--}}
                    @include('site.category._filter_summary')

                    {{--Include sorting topbar--}}
                    @include('site.category._sorting_topbar')

                    {{-- Include results --}}
                    @include('site.category._results', ['products' => $products])
                </div>

            </div>

    @endsection