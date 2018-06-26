<?php

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

?>
<div class="row">
	<div class="col-md-8">
		<h3>Willkommen auf Kostenlos-Wlan.in!</h3>
		<p>
			Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
		</p>
		<center class="my-3"><?php Util::renderAd(AD_TYPE_LEADERBOARD); ?></center>
		<h3>Hotspot-Karte</h3>
		<div id="homeMap" class="my-2"></div>
	</div>

	<div class="col-md-4">
		<center class="my-3"><?php Util::renderAd(AD_TYPE_BLOCK); ?></center>
		<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/KostenlosWLAN?ref_src=twsrc%5Etfw">Tweets by KostenlosWLAN</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> 
	</div>
</div>