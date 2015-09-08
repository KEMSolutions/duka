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
    <form class="ui form" role="form" method="post" action="{{ route('auth.account.action') }}">
        {!! csrf_field() !!}

        {{-- Personal details --}}
        <h4 class="ui dividing header">@lang('boukem.contact')</h4>

        <div class="field">
            <label>@lang('boukem.name')</label>
            <input type="text" name="name" value="{{ $user->name }}" required />
        </div>

        <div class="field">
            <label>@lang('boukem.email') &amp; @lang('boukem.phone')</label>
            <div class="fields">
                <div class="ten wide field">
                    <input type="email" name="email" value="{{ $user->email }}" required />
                </div>
                <div class="six wide field">
                    <input type="tel" name="phone" value="{{ $user->phone }}" placeholder="+xx (xxx) xxx xxxx" />
                </div>
            </div>
        </div>

        {{-- Settings --}}
        <h4 class="ui dividing header">@lang('boukem.account')</h4>

        <div class="field">
            <label>@lang('boukem.select_language')</label>
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
            <label>@lang('boukem.new_pass')</label>
            <div class="fields">
                <div class="eight wide field">
                    <input type="password" name="password" placeholder="@lang('boukem.new_pass')" />
                </div>
                <div class="eight wide field">
                    <input type="password" name="password_confirmation" placeholder="@lang('boukem.confirmation')" />
                </div>
            </div>
        </div>

        {{-- Addresses --}}
        @if (count($user->addresses) > 0)
            @foreach ($user->addresses as $address)
                @include('auth._address', [
                    'address' => $address
                ])
            @endforeach
        @endif

        {{-- Add a new address --}}
        @include('auth._address', [
            'address' => $user->newAddressObject()
        ])

        <br /><br />
        <button class="ui button" type="submit">@lang('boukem.update')</button>
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
