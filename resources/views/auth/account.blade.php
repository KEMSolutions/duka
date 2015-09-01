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
    <form class="ui form" method="post" action="{{ route('auth.account.action') }}">
        {!! csrf_field() !!}

        {{-- Personal details --}}
        <h4 class="ui dividing header">Contact Info</h4>

        <div class="field">
            <label>Name</label>
            <input type="text" name="name" value="{{ $user->name }}" required />
        </div>

        <div class="field">
            <label>Email & Phone #</label>
            <div class="fields">
                <div class="ten wide field">
                    <input type="email" name="email" value="{{ $user->email }}" required />
                </div>
                <div class="six wide field">
                    <input type="tel" name="phone" value="{{ $user->phone }}" />
                </div>
            </div>
        </div>

        {{-- Settings --}}
        <h4 class="ui dividing header">Settings</h4>

        <div class="field">
            <label>Language</label>
            <select class="ui fluid dropdown" name="locale">
                @foreach (Store::locales() as $locale)
                    <option
                        value="{{ $locale->id }}"
                        {{ $locale->id == $user->locale['id'] ? ' selected' : '' }}>

                        {{ $locale->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label>Update password</label>
            <div class="fields">
                <div class="eight wide field">
                    <input type="password" name="password" placeholder="new password" />
                </div>
                <div class="eight wide field">
                    <input type="password" name="password_confirmation" placeholder="confirm password" />
                </div>
            </div>
        </div>

        {{-- Addresses --}}
        <h4 class="ui dividing header">Addresses</h4>
        TODO...<br /><br />

        <button class="ui button" type="submit">Submit</button>
    </form>
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
