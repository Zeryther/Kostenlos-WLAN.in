<?php

use KostenlosWLAN\Place;

$app->get("/search",function(){
	if(isset($_GET["q"]) && !empty($_GET["q"])){
		$query = $_GET["q"];

		$_SESSION["query"] = $query;

		$data = [];
		if(isset($_GET["distanceUnit"]) && $_GET["distanceUnit"] != "km") $data["distanceUnit"] = $_GET["distanceUnit"];
		if(isset($_GET["maxDistance"]) && is_numeric($_GET["maxDistance"]) && (int)$_GET["maxDistance"] != 25) $data["maxDistance"] = (int)$_GET["maxDistance"];
		if(isset($_GET["sorting"]) && !empty($_GET["sorting"]) && $_GET["sorting"] != "next") $data["sorting"] = $_GET["sorting"];

		$dataString = count($data) > 0 ? "?" . http_build_query($data) : "";

		if(is_numeric($query)){
			// ZIP CODE
			$cities = Place::getCitiesFromZipCode($query);
			if(count($cities) > 0){
				$this->reroute("/" . urlencode($query) . $dataString);
			} else {
				$this->reroute("/?msg=placeNotFound");
			}
		} else {
			// CITY NAME
			$zipCodes = Place::getZipCodesFromCity($query);

			if(count($zipCodes) > 0){
				$this->reroute("/" . urlencode(Place::capitaliseCityName($query)) . $dataString);
			} else {
				$this->reroute("/?msg=placeNotFound");
			}
		}
	} else {
		$this->reroute("/");
	}
});