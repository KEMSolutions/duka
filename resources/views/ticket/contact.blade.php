@extends('app')

@section('title')
@lang("boukem.contact") - @parent
@stop

@section('content')

<div class="ui vertical stripe quote segment">
    <div class="ui equal width stackable internally celled grid">
      <div class="center aligned row">
        <div class="column">
          
			<h2 class="ui center aligned icon header">
				<i class="circular envelope icon"></i>
				@lang("boukem.email")
			</h2>
			<p>
				<a href="mailto:{{ Store::info()->support->email }}">{{ Store::info()->support->email }}</a>
			</p>

        </div>
        <div class="column">
          
			<h2 class="ui center aligned icon header">
				<i class="circular phone icon"></i>
				@lang("boukem.phone")
			</h2>
			<p>
				<a href="tel:{{ Store::info()->support->phone->number }}">
				    {{ Store::info()->support->phone->vanity }}
				</a>
			</p>
        </div>
      </div>
    </div>
  </div>

@endsection
