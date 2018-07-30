<?php

use KostenlosWLAN\Util;

if(isset($_GET["msg"]) && !empty($_GET["msg"])){
	$msg = $_GET["msg"];

	if($msg == "loggedIn"){
		Util::createAlert($msg,"Du wurdest erfolgreich eingeloggt.",ALERT_TYPE_SUCCESS,true);
	} else if($msg == "placeNotFound"){
		Util::createAlert($msg,"Die angegebene Postleitzahl/Stadt konnte nicht gefunden werden.",ALERT_TYPE_DANGER,true);
	} else if($msg == "failedToTrack"){
		Util::createAlert($msg,"Dein Standort konnte nicht berechnet werden.",ALERT_TYPE_DANGER,true);
	} else if($msg == "unknownHotspot"){
		Util::createAlert($msg,"Dieser Hotspot konnte nicht gefunden werden.",ALERT_TYPE_DANGER,true);
	} else if($msg == "loggedOut"){
		Util::createAlert($msg,"Du wurdest erfolgreich ausgeloggt.",ALERT_TYPE_SUCCESS,true);
	}
}

?><div id="home">
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

		<center class="mt-3"><?php Util::renderAd(AD_TYPE_LEADERBOARD); ?></center>
	</div>
</div>