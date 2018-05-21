<?php

$app->bind("/:query",function($params){
	$query = $params["query"];
	if(is_numeric($query)){
		// ZIP CODE
		$cities = Place::getCitiesFromZipCode($query);
		if(count($cities) > 0){
			$data = [
				"title" => "Kostenlose Hotspots in " . $query,
				"zipCodes" => [(int)$query],
				"cities" => $cities,
				"query" => $query,
				"wrapperHeadline" => Util::formatNumber(Place::getTotalResultsFromZipCode($query)) . " Ergebnisse gefunden in " . $query
			];
		
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Results.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		} else {
			$this->reroute("/?msg=placeNotFound");
		}
	} else {
		// CITY NAME
		$zipCodes = Place::getZipCodesFromCity($query);

		if(count($zipCodes) > 0){
			$query = Place::capitaliseCityName($query);

			$data = [
				"title" => "Kostenlose Hotspots in " . $query,
				"zipCodes" => $zipCodes,
				"cities" => [$query],
				"query" => $query,
				"wrapperHeadline" => Util::formatNumber(Place::getTotalResultsFromCity($query)) . " Ergebnisse gefunden in " . $query
			];
		
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Results.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		} else {
			$this->reroute("/?msg=placeNotFound");
		}
	}
});