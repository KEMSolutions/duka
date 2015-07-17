<!DOCTYPE html>
<html lang="{{ Localization::getCurrentLocale() }}" >
<head>
	{{-- TODO : include b2b functionnality --}}

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<link rel="icon" href="{{ url('/') . "/img/favicon.png"}}"/>

	{{-- TODO : include dynamic name based on each store name--}}
	<title>{{ Store::info()->name }}</title>

	{{-- TODO: include page description if any--}}

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- Required -->
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="//cdn.kem.guru/css/outdatedBrowser.min.css" rel="stylesheet">

	<!-- Semantic UI css dependencies -->
	<link rel="stylesheet" href="{{ asset('css/semantic-ui/transition.min.css') }}"/>
	<link href="{{ asset('/css/semantic-ui/dropdown.css') }}" rel="stylesheet">

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

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
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="/js_assets/js.cookie.js"></script>
	<script src="//cdn.kem.guru/boukem/spirit/js/gcc_ressources.js.gz"></script>
	<script src="/js_assets/mixitup/jquery.mixitup.init.js"></script>
	<script src="/js_assets/blur/blur.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

	<!-- Semantic ui dependencies -->
	<script src="/js_assets/semantic-ui/transition.min.js"></script>
	<script src="/js_assets/semantic-ui/dropdown.min.js"></script>

	@include("_dynamic_resources")

	<script src="/js/prod/boukem2.js"></script>
	@yield("scripts")
</body>


</html>
