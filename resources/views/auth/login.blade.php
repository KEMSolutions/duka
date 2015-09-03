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
        <form class="ui form" role="form" method="post" action="{{ route('auth.login.action') }}">
            {!! csrf_field() !!}

            {{-- Email --}}
            <div class="field">
                <label>@lang('boukem.email')</label>
                <input type="email" name="email" value="{{ old('email') }}" required />
            </div>

            {{-- Password --}}
            <div class="field">
                <label>@lang('boukem.password')</label>
                <input type="password" name="password" required />
            </div>

            {{-- Remember me checkbox --}}
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="remember" tabindex="0" class="hidden">
                    <label>@lang('boukem.remember')</label>
                </div>
            </div>

            <button class="ui button" type="submit">@lang('boukem.log_in')</button>

            <a class="btn btn-link" href="{{ route('auth.reset') }}">@lang('passwords.password_forgotten')</a>
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
