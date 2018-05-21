<?php

if(isset($_GET["msg"]) && !empty($_GET["msg"])){
	$msg = $_GET["msg"];

	if($msg == "loggedIn"){
		Util::createAlert($msg,"Du wurdest erfolgreich eingeloggt.",ALERT_TYPE_SUCCESS,true);
	} else if($msg == "placeNotFound"){
		Util::createAlert($msg,"Die angegebene Postleitzahl/Stadt konnte nicht gefunden werden.",ALERT_TYPE_DANGER,true);
	} else if($msg == "failedToTrack"){
		Util::createAlert($msg,"Dein Standort konnte nicht berechnet werden.",ALERT_TYPE_DANGER,true);
	}
}

?>
alol