<?php

$app->bind("/track",function(){
	$ip = Util::getIP();
	$geoIP = Util::geoIPData($ip);

	if(is_array($geoIP) && isset($geoIP["city"])){
		$city = isset($geoIP["city"]) ? Util::fixUmlaut($geoIP["city"]) : null;

		if($city != null){
			$this->reroute("/search?q=" . $city);
		} else {
			$this->reroute("/?msg=failedToTrack");
		}
	} else {
		$this->reroute("/?msg=failedToTrack");
	}
});