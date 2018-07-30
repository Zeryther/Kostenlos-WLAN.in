<!DOCTYPE html>
<html lang="de">
	<head>
		<title>
			KostenlosWLAN
		</title>

		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<meta http-equiv="x-ua-compatible" content="ie=edge"/>
		<meta name="apple-mobile-web-app-capable" content="yes">

		<meta name="og:site_name" content="Kostenlos WLAN" />
		<meta name="og:title" content="<?= $title; ?>"/>
		<meta name="og:description" content="<?= $description; ?>" />
		<meta name="og:locale" content="de" />

		<meta name="twitter:title" content="<?= $title; ?>"/>
		<meta name="twitter:description" content="<?= $description; ?>" />
		<meta name="twitter:image" content="<?= $twitterImage; ?>" />
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@KostenlosWLAN" />

		<noscript><meta http-equiv="refresh" content="0; URL=https://gigadrivegroup.com/badbrowser"></noscript>

		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#941890">
		<meta name="msapplication-TileColor" content="#941890">
		<meta name="theme-color" content="#941890">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous"/>
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet"> 
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>

		<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
		<link rel="stylesheet" type="text/css" href="assets/css/star-rating-svg.css" />

		<script type="text/javascript" src="assets/js/app.js"></script>
		<script type="text/javascript" src="assets/js/app.js"></script>
		<script type="text/javascript" src="assets/js/jquery.star-rating-svg.min.js"></script>

		<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
		<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
		<script>
			window.addEventListener("load", function(){
			window.cookieconsent.initialise({
			"palette": {
				"popup": {
				"background": "#64386b",
				"text": "#ffcdfd"
				},
				"button": {
				"background": "#f8a8ff",
				"text": "#3f0045"
				}
			},
			"content": {
				"message": "Diese Webseite verwendet Cookies um eine bessere Erfahrung zu bieten.",
				"dismiss": "Verstanden!",
				"link": "Mehr Infos",
				"href": "https://gigadrivegroup.com/legal/privacy-policy"
			}
			})});
		</script>
	</head>

	<body>
		<nav class="navbar navbar-expand-lg navbar-dark" id="mainHeader">
			<div class="container">
				<div class="brand"><a class="navbar-brand" href="/">kostenlos-wlan.in</a></div>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<form class="form-inline mr-auto" id="search" method="get" action="/search">
						<div class="form-group" style="display: block;">
							<label for="searchBox">Ich suche kostenloses WLAN in</label>
							<input id="searchBox" name="q" class="form-control mr-sm-2" type="search" aria-label="Search" autocomplete="off" spellcheck="off">
						</div>

						<div class="buttons">
							<button type="submit" class="btn btn-warning customBtn">Suchen</button>
							
							<a href="/track">
								<button type="button" class="btn btn-warning customBtn">Orte mich!</button>
							</a>
						</div>
					</form>

					<ul class="navbar-nav">
						<li class="nav-item dropdown myAccountBox">
							<a href="#" class="nav-link dropdown-toggle" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Mein Konto <i class="ml-1 fas fa-user"></i>
							</a>

							<div class="dropdown-menu dropdown-menu-right" aria-labelledBy="accountDropdown">
								<a data-no-instant href="/login" class="dropdown-item">Anmelden</a>
								<a data-no-instant href="/register" class="dropdown-item">Registrieren</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="container">
			<div class="wrapper">
				<div id="home">
					<img src="assets/img/hscr.jpg" id="hscr"/>

					<div id="ybox">
						<div id="ybp" class="mx-auto">
							<div id="ybcontenttop" class="mx-auto">
								<p id="line1">
									Du suchst kostenloses WLAN in deiner Stadt?
								</p>

								<p id="line2">
									Dann bist du hier genau richtig.
								</p>
							</div>

							<hr/>

							<div id="ybcontentbottom">
								<form method="get" action="/search">
									<div id="homeSearchForm" class="mx-auto">
										<div class="form-group" style="display: block;">
											<label for="homeSearchBox">Gebe deine Stadt an und lege los!</label>
											<input id="homeSearchBox" name="q" class="form-control mr-sm-2" type="search" aria-label="Search" autocomplete="off" spellcheck="off">
										</div>

										<button type="submit" class="btn btn-warning customBtn">Suchen</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<small>
				<?php

					$firstYear = 2018;
					$currentYear = date("Y");

					$s = $currentYear;
					if($firstYear != $currentYear) $s = $firstYear . "-" . $currentYear;

				?>
				<div class="mt-2">
					<div class="float-left">
						<a href="https://gigadrivegroup.com/legal/contact" target="_blank">Impressum</a> &bull; <a href="https://gigadrivegroup.com/legal/terms-of-service" target="_blank">Allgemeine Geschäftsbedingungen</a> &bull; <a href="https://gigadrivegroup.com/legal/privacy-policy" target="_blank">Datenschutzerklärung</a> &bull; <a href="https://gigadrivegroup.com/legal/disclaimer" target="_blank">Haftungsausschluss</a><br/>
						&copy; Copyright <a href="https://gigadrivegroup.com" target="_blank">Gigadrive Group</a> <?= $s; ?> - Alle Rechte vorbehalten.
					</div>

					<div class="float-right">
						<a href="https://twitter.com/KostenlosWLAN" target="_blank" class="clearUnderline">
							<img src="/assets/img/social/new/twitter/32.png"/>
						</a>
					</div>
				</div>
			</small>
		</div>

		<!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY_PUBLIC; ?>&callback=initMap"> </script>-->
		<script data-no-instant async defer src="/api/mapsScript"> </script>

		<script src="/assets/js/instantclick.min.js" data-no-instant></script>
		<script data-no-instant>InstantClick.init();InstantClick.on("change",function(){$("time.timeago").timeago();initMap();function updateRatingCommentCount(){$("textarea.countedArea").each(function(){let length = $(this).val().length;let remaining = $(this).attr("maxlength")-length;let counter = $($(this).attr("data-counter"));if(counter != null)counter.html(remaining.toString());});}updateRatingCommentCount();$("textarea.countedArea").keyup(function(){updateRatingCommentCount();});if($("#ratingCommentStars") != null){$("#ratingCommentStars").starRating({strokeColor: '#894A00',strokeWidth: 10,starSize: 30,disableAfterRate: false,starShape: "rounded",forceRoundUp: true,callback: function(currentRating, $el){ $("#ratingValue").val(currentRating); }});}if($(".starRatingReadOnly") != null){$(".starRatingReadOnly").starRating({strokeColor: '#894A00',strokeWidth: 10,starSize: 30,disableAfterRate: false,starShape: "rounded",forceRoundUp: true,readOnly: true});}if($(".starRatingReadOnlySmall") != null){$(".starRatingReadOnlySmall").starRating({strokeColor: '#894A00',strokeWidth: 10,starSize: 15,disableAfterRate: false,starShape: "rounded",forceRoundUp: true,readOnly: true});}});</script>
	</body>
</html>