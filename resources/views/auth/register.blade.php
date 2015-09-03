@extends('app')

@section("custom_css")
    <link href="{{ asset('/css/cartdrawer.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/css/animate.css')}}"/>
    <link rel="stylesheet" href="{{ asset('/css/product_card.css') }}"/>
@endsection

@section('content')

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
        <form class="ui form" role="form" method="post" action="{{ route('auth.register.action') }}">
            {!! csrf_field() !!}

            {{-- Name --}}
            <div class="field">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required />
            </div>

            {{-- Email --}}
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required />
            </div>

            {{-- Password --}}
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required />
            </div>

            {{-- Confirm password --}}
            <div class="field">
                <label>Confirm password</label>
                <input type="password" name="password_confirmation" required />
            </div>

            <button class="ui button" type="submit">Register</button>
        </form>
    </div>

    <div class="two wide column"></div>
</div>
<br />
<br />

@endsection

@section("scripts")
    <script>
        $(".indicator-down:first").hide();
        $(".section-title:first").hide();
    </script>
@endsection
