<!DOCTYPE html>
<html lang="{{ Localization::getCurrentLocale() }}" >
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<link rel="icon" href="{{ url('/') . "/img/favicon.png"}}"/>

	<title>{{ Store::info()->name }}</title>

	{{-- TODO: include page description if any--}}

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300|Lato' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- Semantic UI css dependencies -->
	<link rel="stylesheet" href="{{ asset('/semantic/prod/semantic.css') }}"/>

	<!-- Required -->
	<link href="//cdn.kem.guru/css/outdatedBrowser.min.css" rel="stylesheet">
	<link href="{{ asset('/css/prod/app.css') }}" rel="stylesheet">

	<!-- Custom css -->
	@yield("custom_css")
	<!-- Color specific css -->
	@include("_color_css")

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	@include("layout._header")

	@include("layout._drawer")

	@include("layout._messages")


	@yield("content")


	@include("layout._footer")

	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="/js_assets/js.cookie.js"></script>
	<script src="//cdn.kem.guru/boukem/spirit/js/gcc_ressources.js.gz"></script>
	<script src="/js_assets/blur/blur.min.js"></script>

	<!-- Semantic ui dependencies -->
	<script src="/semantic/prod/semantic-2.1.4.min.js"></script>

	@include("_dynamic_resources")

	<script src="/js/prod/duka.js"></script>

	@yield("scripts")

	{{-- Include cart dimmer for mobile --}}
	@include("layout._dimmer")
</body>


</html>
