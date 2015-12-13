@extends("app")

@section("content")

        {{-- Display search results --}}
        @if (count($results->organic_results))
            @include('site.search._results', ['results' => $results])
        @else
            @include('site.search._no-results-found')
        @endif

@endsection
