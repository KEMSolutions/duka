@extends('app')

@section("custom_css")
	<link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
	<link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
@endsection

@section('content')

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

{{-- Errors --}}
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<br />
<div class="ui grid container">
    <div class="two wide column"></div>

    <div class="twelve wide column">
        <form class="ui form" role="form" method="post" action="{{ route('auth.reset') }}">
            {!! csrf_field() !!}

            {{-- Email --}}
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required />
            </div>

            <button class="ui button" type="submit">Submit</button>
        </form>
    </div>

    <div class="two wide column"></div>
</div>
<br />
<br />

@endsection
