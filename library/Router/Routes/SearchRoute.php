<?php

$app->get("/search",function(){
	if(isset($_GET["q"]) && !empty($_GET["q"])){
		$query = $_GET["q"];

		$data = [];
		if(isset($_GET["distanceUnit"]) && $_GET["distanceUnit"] != "km") $data["distanceUnit"] = $_GET["distanceUnit"];

		$dataString = count($data) > 0 ? "?" . http_build_query($data) : "";

		if(is_numeric($query)){
			// ZIP CODE
			$cities = Place::getCitiesFromZipCode($query);
			if(count($cities) > 0){
				$this->reroute("/" . $query . $dataString);
			} else {
				$this->reroute("/?msg=placeNotFound");
			}
		} else {
			// CITY NAME
			$zipCodes = Place::getZipCodesFromCity($query);

			if(count($zipCodes) > 0){
				$this->reroute("/" . Place::capitaliseCityName($query) . $dataString);
			} else {
				$this->reroute("/?msg=placeNotFound");
			}
		}
	} else {
		$this->reroute("/");
	}
});