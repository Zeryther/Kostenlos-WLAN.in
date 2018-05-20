<!DOCTYPE html>
<html>
	<head>
		<title>lol asd</title>

		<meta name="theme-color" content="#941890">
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<meta http-equiv="x-ua-compatible" content="ie=edge"/>
		<meta name="apple-mobile-web-app-capable" content="yes">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous"/>
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"/>

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
			<div class="brand"><a class="navbar-brand" href="#">kostenlos-wlan.in</a></div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<form class="form-inline" id="search">
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
			</div>
		</nav>
		
		<?php var_dump($content_for_layout); ?>
	</body>
</html>