@extends('app')

@section('content')

@if (session('status'))
<div class="ui success message">
  <i class="close icon"></i>
  <p>{{ session('status') }}</p>
</div>
    
@endif

{{-- Errors --}}
@if (count($errors) > 0)
<div class="ui error message">
  <i class="close icon"></i>
  <div class="header">
    @lang("boukem.error_occured")
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
    <div class="two wide column"></div>

    <div class="twelve wide column">
        <form class="ui form" role="form" method="post" action="{{ route('auth.reset') }}">
            {!! csrf_field() !!}

            {{-- Email --}}
            <div class="field">
                <label>@lang('boukem.email')</label>
                <input type="email" name="email" value="{{ old('email') }}" required />
            </div>

            <button class="ui button" type="submit">@lang('boukem.change_pass')</button>
        </form>
    </div>

    <div class="two wide column"></div>
</div>
<br />
<br />

@endsection
