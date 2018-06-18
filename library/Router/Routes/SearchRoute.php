<?php

$app->get("/search",function(){
	if(isset($_GET["q"]) && !empty($_GET["q"])){
		$query = $_GET["q"];

		if(is_numeric($query)){
			// ZIP CODE
			$cities = Place::getCitiesFromZipCode($query);
			if(count($cities) > 0){
				$this->reroute("/" . $query);
			} else {
				$this->reroute("/?msg=placeNotFound");
			}
		} else {
			// CITY NAME
			$zipCodes = Place::getZipCodesFromCity($query);

			if(count($zipCodes) > 0){
				$this->reroute("/" . Place::capitaliseCityName($query));
			} else {
				$this->reroute("/?msg=placeNotFound");
			}
		}
	} else {
		$this->reroute("/");
	}
});