<html>
	<head>
		<link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

		<link rel="stylesheet" href="{{ asset('/semantic/prod/semantic.css') }}"/>

		<style>
			.super-big-title {
				font-size: 7rem !important;
			}

			.big-title {
				font-size: 2rem !important;
			}

			title {
				font-size: 1rem !important;
			}

			body {
				background-color: #fff;
				color: #333;
			}

			.error-template {
				padding-top: 10%;
			}

		</style>
	</head>
	<body>

		<div class="ui container error-template">

			<h1 class="ui header super-big-title">
				:(
				
				<span style="float:right">
					<img src="{{  Store::logo() }}" alt="{{ Store::info()->name }}"/>
				</span>
			</h1>

			<h2 class="ui header big-title">
				@lang("boukem.error_message")
			</h2>

			<h3 class="ui header title">
				@yield('details')
			</h3>

			<p style="text-align: center; margin-top: 2rem">Code: @yield('status', '??')</p>

			<a href="{{ route("home") }}">
				<button class="ui inverted green button">
					@lang("boukem.button_error")
				</button>
			</a>


		</div>
	</body>
</html>
