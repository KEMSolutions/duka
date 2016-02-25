<!DOCTYPE html>
<html lang="{{ Localization::getCurrentLocale() }}" >
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<meta name="duka-localizations-and-endpoints-url" content="{{ route('localizationsAndEndpoints') }}"/>
	{{-- Insert meta tags for client side currency localization --}}
	@if (Utilities::currencyCodeForUser() && Utilities::currencyCodeForUser() !== "CAD")
		<meta name="user-currency-code" content="{{ Utilities::currencyCodeForUser() }}">
		<meta name="user-currency-rate" content="{{ Utilities::getAlternateCurrencyRate(Utilities::currencyCodeForUser()) }}">
	@endif

	@if(getenv("FACEBOOK_APP_ID"))
		<meta property="fb:app_id" content="{{ getenv("FACEBOOK_APP_ID") }}" />
	@endif

	@yield("custom_metas")

	@if(Auth::check())
		<meta name="user-login" content="{{ Auth::user()->id}}"/>
	@endif

	<meta name="user-login"/>
	<link rel="icon" type="image/png" href="{{ url('/') . "/favicon.png"}}"/>
	<link rel='apple-touch-icon' type='image/png' href='/apple-touch-icon.png'>

	@if(count(Store::info()->blogs) > 0)
	<link rel="alternate" type="application/atom+xml" title="{{ Store::info()->name }} - @lang('boukem.blog')" href="{{ action('BlogController@getFeed') }}" />
	@endif

	<title>
@section('title')
{{ Store::info()->name }}
@show
	</title>

	{{-- TODO: include page description if any--}}

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300|Lato' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- Semantic UI css dependencies -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.css"/>

	<!-- Required -->
	<link href="//cdn.kem.guru/css/outdatedBrowser.min.css" rel="stylesheet">
	<link href="{{ url('css/main.css') }}" rel="stylesheet">

	<!-- Custom css -->
	@yield("custom_css")

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	{{-- Tracking scripts --}}
	@if (Config::get('services.mixpanel'))
		@include("analytics.mixpanel._tracking")
	@endif

	@if (Config::get('services.ganalytics'))
		@include("analytics.GAE._tracking")
	@endif

	@if (Config::get('services.piwik.id') && Config::get('services.piwik.domain'))
		@include("analytics._piwik")
	@endif


</head>
<body class="duka-container">

	@include("layout._slider")

	<div class="pusher">
		@include("layout._header")

		@include("layout._messages")


		@yield("content")


		@include("layout._footer")
	</div>

	{{-- Templates for Vue.js--}}
	@include("templates._product_card_template")


	{{-- Dependencies scripts --}}
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="/js_assets/js.cookie.js"></script>

	{{-- Semantic ui dependencies --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.js"></script>

	{{-- Vue.js --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.16/vue.js"></script>

	{{-- Duka.js --}}
	<script src="/js/prod/duka.js"></script>

	{{-- Tracking scripts initialization --}}
	@if (Config::get('services.mixpanel'))
		@include("analytics.mixpanel._init")
	@endif

	@if (Config::get('services.ganalytics'))
		@include("analytics.GAE._init")
	@endif

	@yield("custom_scripts")

</body>


</html>
