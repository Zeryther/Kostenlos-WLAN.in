<?php

if(isset($_GET["msg"]) && !empty($_GET["msg"])){
	$msg = $_GET["msg"];

	if($msg == "loggedIn"){
		Util::createAlert("loggedIn","Du wurdest erfolgreich eingeloggt.",ALERT_TYPE_SUCCESS,true);
	}
}

?>
alol