@extends("app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
    @endsection

    @section("content")
        {{-- Include header --}}
        @include("site.category._header", ['name' => $name])

            <div class="container-fluid">
                {{-- Include breadcrumbs --}}
                @include('site.category._breadcrumbs')

                {{-- Include filter sidebar --}}
                @include('site.category._filter_sidebar')

                {{-- Include filter summary container --}}
                @include('site.category._filter_summary')

                {{-- Include sorting topbar --}}


                {{-- Include results --}}
                @include('site.category._results', ['results' => $products])
            </div>

    @endsection



@stop