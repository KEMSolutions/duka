@extends('app')

@section('content')

<br />
<div class="ui grid container">
    <div class="five wide column"></div>

    <div class="six wide column">
        <h1 class="ui header">@lang("boukem.sign_up")</h1>

        <form class="ui form" role="form" method="post" action="{{ route('auth.register.action') }}">
            {!! csrf_field() !!}

            {{-- Name --}}
            <div class="field">
                <label>@lang('boukem.name')</label>
                <input type="text" name="name" value="{{ Request::input('name', old('name')) }}" required />
            </div>

            {{-- Email --}}
            <div class="field">
                <label>@lang('boukem.email')</label>
                <input type="email" name="email" value="{{ Request::input('email', old('email')) }}" required />
            </div>

            {{-- Password --}}
            <div class="field">
                <label>@lang('boukem.password')</label>
                <input type="password" name="password" required />
            </div>

            {{-- Confirm password --}}
            <div class="field">
                <label>@lang("boukem.password") @lang('boukem.confirmation')</label>
                <input type="password" name="password_confirmation" required />
            </div>

            <button class="ui btn btn-one btn-one-inverted" type="submit">@lang('boukem.sign_up')</button>
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
