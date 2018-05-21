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
	
	$c = $useKilometers == true ? 6371 : 3959;
	$maxDistance = 25;
	
	if(is_numeric($query)){
		// ZIP CODE
		$cities = Place::getCitiesFromZipCode($query);
		if(count($cities) > 0){
			$s = "SELECT *,(?*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude)))) AS distance FROM hotspots HAVING distance <= ? ORDER BY distance";
			
			$mysqli = Database::Instance()->get();
			$stmt = $mysqli->prepare($s);
			$stmt->bind_param("idddd",$c,$x,$y,$x,$maxDistance);
			if($stmt->execute()){
				$result = $stmt->get_result();
				
				if($result->num_rows){
					while($row = $result->fetch_assoc()){
						$h = Hotspot::getHotspotFromData($row["id"],$row["name"],$row["address"],$row["zipCode"],$row["city"],$row["latitude"],$row["longitude"],$row["creator"],$row["time"]);
						$distance = $row["distance"];
						
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
				"wrapperHeadline" => Util::formatNumber(count($hotspots)) . " Ergebnisse gefunden in " . $query
			];
			
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Results.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		} else {
			$this->reroute("/?msg=placeNotFound");
		}
	} else {
		// CITY NAME
		$zipCodes = Place::getZipCodesFromCity($query);
		
		if(count($zipCodes) > 0){
			$s = "SELECT *,(?*acos(cos(radians(?))*cos(radians(latitude))*cos(radians(longitude)-radians(?))+sin(radians(?))*sin(radians(latitude)))) AS distance FROM hotspots HAVING distance <= ? ORDER BY distance";
			
			$mysqli = Database::Instance()->get();
			$stmt = $mysqli->prepare($s);
			$stmt->bind_param("idddd",$c,$x,$y,$x,$maxDistance);
			if($stmt->execute()){
				$result = $stmt->get_result();
				
				if($result->num_rows){
					while($row = $result->fetch_assoc()){
						$h = Hotspot::getHotspotFromData($row["id"],$row["name"],$row["address"],$row["zipCode"],$row["city"],$row["latitude"],$row["longitude"],$row["creator"],$row["time"]);
						$distance = $row["distance"];
						
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
				"wrapperHeadline" => Util::formatNumber(count($hotspots)) . " Ergebnisse gefunden in " . $query
			];
			
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Results.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		} else {
			$this->reroute("/?msg=placeNotFound");
		}
	}
});