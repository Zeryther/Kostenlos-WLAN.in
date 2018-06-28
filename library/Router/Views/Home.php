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
		<h3>Willkommen auf Kostenlos-WLAN.in!</h3>
		<p>
			Kostenlos WLAN ist eine Liste für kostenfrei angebotene Wireless LAN Verbindungen. Open WLAN wird immer häufiger angeboten. Viele Restaurants, Cafés usw. in deiner Nähe bieten sicher ein ähnliches Angebot! Derzeit haben wir <b id="homeHotspotCount"><i class="fas fa-spinner fa-pulse"></i></b> Hotspots in unserer Datenbank.<br/><br/>
			Erstelle dir einen Account um etwas beizutragen, da wir alleine sicher nicht alle Hotspots finden.<br/>
			Einer der Hotspots funktioniert nicht oder wurde abgebaut? Eingeloggte Mitglieder können auf der Hotspotseite ein Problem mit einem Hotspot melden, damit wir die Daten aktualisieren können.<br/><br/>
			Viel Spaß beim kostenfreien Surfen!
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