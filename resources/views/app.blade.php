<!DOCTYPE html>
<html lang="{{ Localization::getCurrentLocale() }}" >
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}"/>
	<link rel="icon" type="image/png" href="{{ url('/') . "/favicon.png"}}"/>
	<link rel='apple-touch-icon' type='image/png' href='/apple-touch-icon.png'>

	<title>{{ Store::info()->name }}</title>

	{{-- TODO: include page description if any--}}

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300|Lato' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- Semantic UI css dependencies -->
	<link rel="stylesheet" href="{{ asset('/semantic/prod/semantic.css') }}"/>

	<!-- Required -->
	<link href="//cdn.kem.guru/css/outdatedBrowser.min.css" rel="stylesheet">
	<link href="{{ url('css/main.css') }}" rel="stylesheet">

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

	{{-- Tracking scripts --}}
	@if (Config::get('services.mixpanel'))
	{{-- Mixpanel --}}
	<script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f)}})(document,window.mixpanel||[]);
mixpanel.init("{{ Config::get('services.mixpanel') }}");</script>
	@endif

	@if (Config::get('services.ganalytics'))
	{{-- Google Analytics --}}
	<script>
	window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
	ga('create', '{{ Config::get('services.ganalytics') }}', 'auto');
	ga('send', 'pageview');
	</script>
	<script async src='https://www.google-analytics.com/analytics.js'></script>
	@endif

	@if (Config::get('services.piwik.id') && Config::get('services.piwik.domain'))
	{{-- Piwik --}}
	<script type="text/javascript">
	  var _paq = _paq || [];
	  _paq.push(['trackPageView']);
	  _paq.push(['enableLinkTracking']);
	  (function() {
	    var u="//{{ Config::get('services.piwik.domain') }}/";
	    _paq.push(['setTrackerUrl', u+'piwik.php']);
	    _paq.push(['setSiteId', {{ Config::get('services.piwik.id') }}]);
	    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
	    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
	  })();
	</script>
	<noscript><p><img src="//{{ Config::get('services.piwik.domain') }}/piwik.php?idsite={{ Config::get('services.piwik.id') }}" style="border:0;" alt="" /></p></noscript>
	@endif


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
