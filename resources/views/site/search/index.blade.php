@extends("...app")

    @section("custom_css")
        <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
        <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
    @endsection

    @section("content")
        <h1>Search Test</h1>

        @if ($results)
            <h2>Results for: <b>{{ $query }}</b></h2>

            <ol>
                @foreach ($results->organic_results as $prod)
                    <li>
                        <a href="{{ route('product', ['slug' => $prod->slug]) }}">
                            {{ $prod->localization->name }}
                        </a>
                    </li>
                @endforeach
            </ol>
        @endif
    @endsection

    @section("scripts")
        <script src="/js/boukem2-utility.js"></script>
        <script src="/js/cart-drawer.js"></script>

        <script>
            $(".indicator-down:first").hide();
        </script>
    @endsection

@stop

