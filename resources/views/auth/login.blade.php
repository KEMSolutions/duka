@extends('app')

@section('content')

{{-- Errors --}}
@if (count($errors) > 0)
<div class="ui error message">
  <i class="close icon"></i>
  <div class="header">
    @lang("boukem.error_occurred")
  </div>
  <ul class="list">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
  </ul>
</div>
@endif

<br />
<div class="ui grid container">
    <div class="five wide column"></div>

    <div class="six wide column">
        <h1 class="ui header">@lang("boukem.log_in")</h1>
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

            <button class="ui btn btn-one btn-one-inverted" type="submit">@lang('boukem.log_in')</button>

            <a class="btn btn-link" href="{{ route('auth.reset') }}">@lang('passwords.password_forgotten')</a>
        </form>
    </div>

    <div class="five wide column"></div>
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
