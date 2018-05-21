<?php

$app->bind("/",function(){
	$data = [
		"title" => "Finde kostenlose WLAN Hotspots in deiner Nähe!"
	];

	return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Home.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
});