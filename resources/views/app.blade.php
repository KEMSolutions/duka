<!DOCTYPE html>
<html lang="{{ Localization::getCurrentLocale() }}" >
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	@yield("custom_css")
	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- Required -->
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
	<link href="//kle-en-main.com/assets/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="//cdn.kem.guru/css/outdatedBrowser.min.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Laravel</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/') }}">Home</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	@yield('content')


	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

	@yield("scripts")
</body>

<footer class="hidden-print">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="col">
					<h4>Contact us</h4>
					<ul>
						<?php //<li>Téléphone: 514-303-5667</li> ?>
						<li><?php echo Yii::t("app", "Courriel"); ?>: <a href="mailto:support@kle-en-main.com" title="<?php echo Yii::t("app", "Nous envoyer un courriel"); ?>">support@kle-en-main.com</a></li>
						<?php //<li>Skype: <a href="skype:kle-en-main?call" title="Skype us">kle-en-main</a></li> ?>
						<li><?php echo Yii::t("app", "Démocratiser l'accès au web pour les commerces et les professionnels de la santé naturelle oeuvrant en français est notre mission."); ?></li>
					</ul>
				</div>
			</div>

			<div class="col-md-3">
				<div class="col">
					<h4><?php echo Yii::t("app", "Infolettre"); ?></h4>
					<p><?php echo Yii::t("app", "Inscrivez-vous à notre infolettre pour demeurer au fait de nos activités."); ?></p>

					<form class="form-inline" method="post" action="https://kle-en-main.us3.list-manage.com/subscribe/post?u=dd261a289b2e14b803012a5ed&amp;id=c2e3850103" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" novalidate target="_blank">

						<div class="input-group">
							<input type="email" value="" id="mce-EMAIL" name="EMAIL" class="form-control" placeholder="<?php echo Yii::t("app", "Votre courriel..."); ?>" required>
							<div style="position: absolute; left: -5000px;"><input type="text" name="b_dd261a289b2e14b803012a5ed_c2e3850103" value=""></div>
                            <span class="input-group-btn">
                                <input class="btn btn-primary" type="submit" value="<?php echo Yii::t("app", "S'inscrire"); ?>" name="subscribe" id="mc-embedded-subscribe">
                            </span>
						</div>
					</form>
				</div>
			</div>

			<div class="col-md-3">
				<div class="col col-social-icons">
					<h4><?php echo Yii::t("app", "Nous suivre"); ?></h4>
					<a href="http://www.linkedin.com/company/kl%C3%A9-en-main"><i class="fa fa-linkedin"></i></a>
					<a href="https://twitter.com/KleEnMain"><i class="fa fa-twitter"></i></a>
					<a href="http://eepurl.com/KNJXz"><i class="fa fa-envelope"></i></a>
				</div>
			</div>

			<div class="col-md-3">
				<div class="col">
					<h4><?php echo Yii::t("app", "Console KEM"); ?> 0.9 β</h4>
					<p>
						<?php echo Yii::t("app", "Laissez nous savoir ce que vous pensez de la console Klé-en-main, rapportez nous les bogues et aidez nous à l'améliorer. Vous pouvez même nous envoyer une capture d'écran; utilisez simplement le bouton suivant."); ?><br>

						<br /><br />
						<a href="/contact.html" data-uv-trigger class="btn btn-two"><?php echo Yii::t("app", "Contactez notre équipe technique!"); ?></a>
					</p>
				</div>
			</div>
		</div>

		<hr />

		<div class="row">
			<div class="col-lg-10 copyright">
				<?php echo date('Y') . " " . Yii::t("app", "&copy; Solutions Klé-en-main. Tous droits réservés."); ?><br>
				<p style="opacity:0.8"><?php echo Yii::t("app", "Klé-en-main™ se réserve le droit de refuser toute demande de service selon la compatibilité de vos activités commerciales avec nos services et le territoire où vous êtes situés. Les clients de Klé-en-main™ s'engagent à ne pas copier, reproduire ou utiliser à d'autres fins commerciales le contenu de la boutique Klé-en-main™ (incluant les photographies et descriptions de produits) sans autorisation écrite préalable de Klé-en-main™. En cas de non respect, Klé-en-main™ se réserve le droit d'interrompre son service, de retenir les sommes dues et d'entreprendre des procédures légales. Klé-en-main™ n'est pas responsable de toute interruption temporaire des services en ligne causée par ses fournisseurs ou des activités de maintenance informatique."); ?></p>
				<?PHP //<a href="#">Terms of use</a> |
				//<a href="#">Privacy policy</a> ?>
			</div>
			<div class="col-lg-2 footer-logo">
				<img src="/images/carousel/logo-white.png" alt="Klé-en-main" style="opacity:0.7">
			</div>
		</div>
	</div>
</footer>
</html>
