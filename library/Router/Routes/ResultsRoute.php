<?php

$app->bind("/:query",function($params){
	$query = $params["query"];
	
	$hotspots = [];
	
	$x = 0;
	$y = 0;
	$latLng = Place::getLatLngFromQuery($query);
	if($latLng != null){
		$x = $latLng["latitude"];
		$y = $latLng["longitude"];
	}
	
	$useKilometers = true;
	if(isset($_GET["distanceUnit"]) && strtolower($_GET["distanceUnit"]) != "km") $useKilometers = false;

	$maxDistance = 25;
	if(isset($_GET["maxDistance"]) && is_numeric($maxDistance)) $maxDistance = (int)$_GET["maxDistance"];

	if($maxDistance < FILTER_MAX_DISTANCE_MINIMUM) $maxDistance = FILTER_MAX_DISTANCE_MINIMUM;
	if($maxDistance > FILTER_MAX_DISTANCE_MAXIMUM) $maxDistance = FILTER_MAX_DISTANCE_MAXIMUM;
	
	$c = $useKilometers == true ? 6371 : 3959;
	
	if(is_numeric($query)){
		// ZIP CODE
		$cities = Place::getCitiesFromZipCode($query);
		if(count($cities) > 0){
			$s = "SELECT *,(?*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude)))) AS distance FROM hotspots HAVING distance <= ? AND `valid` = 1 ORDER BY distance";
			
			$mysqli = Database::Instance()->get();
			$stmt = $mysqli->prepare($s);
			$stmt->bind_param("idddd",$c,$x,$y,$x,$maxDistance);
			if($stmt->execute()){
				$result = $stmt->get_result();
				
				if($result->num_rows){
					while($row = $result->fetch_assoc()){
						$h = Hotspot::getHotspotFromData($row["id"],$row["name"],$row["address"],$row["zipCode"],$row["city"],$row["latitude"],$row["longitude"],$row["creator"],$row["time"],$row["valid"],$row["googlePlaceId"],$row["photo"],$row["rating"]);
						$distance = (double)$row["distance"];
						
						array_push($hotspots,[$h,$distance]);
					}
				}
			}
			$stmt->close();
			
			$data = [
				"title" => "Kostenlose Hotspots in " . $query,
				"zipCodes" => [(int)$query],
				"cities" => $cities,
				"query" => $query,
				"hotspots" => $hotspots,
				"useKilometers" => $useKilometers,
				"maxDistance" => $maxDistance,
				"wrapperHeadline" => (count($hotspots) == 1) ? Util::formatNumber(count($hotspots)) . " Ergebnis gefunden in " . $query : Util::formatNumber(count($hotspots)) . " Ergebnisse gefunden in " . $query
			];
			
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Results.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		}
	} else {
		// CITY NAME
		$zipCodes = Place::getZipCodesFromCity($query);
		
		if(count($zipCodes) > 0){
			$s = "SELECT *,(?*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude)))) AS distance FROM hotspots HAVING distance <= ? AND `valid` = 1 ORDER BY distance";
			
			$mysqli = Database::Instance()->get();
			$stmt = $mysqli->prepare($s);
			$stmt->bind_param("idddd",$c,$x,$y,$x,$maxDistance);
			if($stmt->execute()){
				$result = $stmt->get_result();
				
				if($result->num_rows){
					while($row = $result->fetch_assoc()){
						$h = Hotspot::getHotspotFromData($row["id"],$row["name"],$row["address"],$row["zipCode"],$row["city"],$row["latitude"],$row["longitude"],$row["creator"],$row["time"],$row["valid"],$row["googlePlaceId"],$row["photo"],$row["rating"]);
						$distance = (double)$row["distance"];
						
						array_push($hotspots,[$h,$distance]);
					}
				}
			}
			$stmt->close();

			$query = Place::capitaliseCityName($query);
			
			$data = [
				"title" => "Kostenlose Hotspots in " . $query,
				"zipCodes" => $zipCodes,
				"cities" => [$query],
				"query" => $query,
				"hotspots" => $hotspots,
				"useKilometers" => $useKilometers,
				"maxDistance" => $maxDistance,
				"wrapperHeadline" => (count($hotspots) == 1) ? Util::formatNumber(count($hotspots)) . " Ergebnis gefunden in " . $query : Util::formatNumber(count($hotspots)) . " Ergebnisse gefunden in " . $query
			];
			
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Results.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		}
	}
});