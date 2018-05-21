<?php

if(isset($title) && !empty($title)){
	$title = $title . " - " . $app["config.site"]["name"];
} else {
	$title = $app["config.site"]["name"];
}

if(!isset($description) || empty($description)){
	$description = DEFAULT_DESCRIPTION;
}

if(!isset($twitterImage) || empty($twitterImage)){
	$twitterImage = DEFAULT_TWITTER_IMAGE;
}

?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<title>
			<?= $title; ?>
		</title>

		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<meta http-equiv="x-ua-compatible" content="ie=edge"/>
		<meta name="apple-mobile-web-app-capable" content="yes">

		<meta name="og:site_name" content="MCSkinHistory" />
		<meta name="og:title" content="<?= $title; ?>"/>
		<meta name="og:description" content="<?= $description; ?>" />
		<meta name="og:locale" content="de" />

		<meta name="twitter:title" content="<?= $title; ?>"/>
		<meta name="twitter:description" content="<?= $description; ?>" />
		<meta name="twitter:image" content="<?= $twitterImage; ?>" />
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@mcskinhistory" />

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

		<?php echo $app->style(array(
				"assets:css/style.css"
			)); ?>
		
		<?php echo $app->script(array(
				"assets:js/jquery.timeago.js",
				"assets:js/app.js"
			)); ?>
	</head>

	<body>
		<nav class="navbar navbar-expand-lg navbar-dark" id="mainHeader">
			<div class="container">
				<div class="brand"><a class="navbar-brand" href="<?php print $app->routeUrl("/"); ?>">kostenlos-wlan.in</a></div>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<form class="form-inline mr-auto" id="search">
						<div class="form-group" style="display: block;">
							<label for="searchBox">Ich suche kostenloses WLAN in</label>
							<input id="searchBox" name="query" class="form-control mr-sm-2" type="search" aria-label="Search">
						</div>

						<div class="buttons">
							<button type="submit" class="btn btn-warning">Suchen</button>
							
							<a href="">
								<button type="button" class="btn btn-warning">Orte mich!</button>
							</a>
						</div>
					</form>

					<?php

						if(Util::isLoggedIn()){
							?>
					<ul class="navbar-nav">
						<li class="nav-item myAccountBox">
							<a href="<?= $app->routeUrl("/account"); ?>" class="nav-link" style="margin-top: 40px" id="accountDropdown" role="button">
								Eingeloggt als<br/><?= Util::getCurrentUser()->getUsername(); ?>
							</a>
						</li>
					</ul>
							<?php
						} else {
							?>
					<ul class="navbar-nav">
						<li class="nav-item dropdown myAccountBox">
							<a href="#" class="nav-link dropdown-toggle" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Mein Konto <i class="ml-1 fas fa-user"></i>
							</a>

							<div class="dropdown-menu dropdown-menu-right" aria-labelledBy="accountDropdown">
								<a href="<?= $app->routeUrl("/login"); ?>" class="dropdown-item">Anmelden</a>
								<a href="<?= $app->routeUrl("/register"); ?>" class="dropdown-item">Registrieren</a>
							</div>
						</li>
					</ul>
							<?php
						}

					?>
				</div>
			</div>
		</nav>

		<div class="container">
			<div class="wrapper">
				<?php

				if(isset($wrapperHeadline) && !empty($wrapperHeadline)){
					?>
				<div class="wrapperHeadline">
					<?= $wrapperHeadline; ?>
				</div>
					<?php
				}

				?>

				<div style="padding: 10px">
					<?= $content_for_layout; ?>
				</div>
			</div>

			<small>
				<?php

					$firstYear = 2018;
					$currentYear = date("Y");

					$s = $currentYear;
					if($firstYear != $currentYear) $s = $firstYear . "-" . $currentYear;

				?>
				<a href="https://gigadrivegroup.com/legal/contact" target="_blank">Impressum</a> &bull; <a href="https://gigadrivegroup.com/legal/terms-of-service" target="_blank">Allgemeine Geschäftsbedingungen</a> &bull; <a href="https://gigadrivegroup.com/legal/privacy-policy" target="_blank">Datenschutzerklärung</a> &bull; <a href="https://gigadrivegroup.com/legal/disclaimer" target="_blank">Haftungsausschluss</a><br/>
				&copy; Copyright <a href="https://gigadrivegroup.com" target="_blank">Gigadrive Group</a> <?= $s; ?> - Alle Rechte vorbehalten.
			</small>
		</div>
	</body>
</html>