<?php

class Place {
	public static function getPlace($zipCode,$name){
		$n = "zipCode_" . $zipCode . "_" . $name;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$place = new Place($zipCode,$name);
			if($place->exists() == true){
				return $place;
			} else {
				return null;
			}
		}
	}

	public static function getGoogleGeocodeData($query){
		$curl = curl_init();
		curl_setopt_array($curl,
			[
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($query) . "&key=" . GOOGLE_MAPS_API_KEY_PRIVATE
			]
		);
		$result = curl_exec($curl);
		curl_close($curl);
		return json_decode($result,true);
	}

	public static function getLatLngFromQuery($query){
		if(is_numeric($query)){
			// ZIP CODE
			$cities = self::getCitiesFromZipCode($query);
			if(count($cities) > 0){
				foreach($cities as $city){
					$place = self::getPlace((int)$query,$city);
					if($place != null && $place->getLatLng() != null && $place->getLatLng()["latitude"] != null && $place->getLatLng()["longitude"] != null){
						return $place->getLatLng();
					}
				}
			}
		} else {
			// CITY NAME
			$zipCodes = self::getZipCodesFromCity($query);
			if(count($zipCodes) > 0){
				foreach($zipCodes as $code){
					$place = self::getPlace($code,$query);
					if($place != null){
						return $place->getLatLng();
					}
				}
			}
		}

		return null;
	}

	public static function getZipCodesFromCity($city){
		$n = "zipCodesCity_" . $city;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$mysqli = Database::Instance()->get();

			$codes = array();

			$stmt = $mysqli->prepare("SELECT `code` FROM `places` WHERE `cityName` LIKE ?");
			$c = "%" . trim($city) . "%";
			$stmt->bind_param("s",$c);
			if($stmt->execute()){
				$result = $stmt->get_result();
				if($result->num_rows){
					while($row = $result->fetch_assoc()){
						if(!in_array($row["code"],$codes)) array_push($codes,$row["code"]);
					}
				}
			}
			$stmt->close();

			CacheHandler::setToCache($n,$codes,20*60);

			return $codes;
		}
	}

	public static function getCitiesFromZipCode($code){
		$n = "citiesZipCode_" . $code;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$mysqli = Database::Instance()->get();

			$cities = array();

			$stmt = $mysqli->prepare("SELECT `cityName` FROM `places` WHERE `code` = ? ORDER BY RAND()");
			$stmt->bind_param("i",$code);
			if($stmt->execute()){
				$result = $stmt->get_result();
				if($result->num_rows){
					while($row = $result->fetch_assoc()){
						if(!in_array($row["cityName"],$cities)) array_push($cities,$row["cityName"]);
					}
				}
			}
			$stmt->close();

			CacheHandler::setToCache($n,$cities,20*60);

			return $cities;
		}
	}

	public static function getTotalResultsFromCity($city){
		$n = "totalResults_" . $city;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$zipCodes = self::getZipCodesFromCity($city);

			if(count($zipCodes) > 0){
				$mysqli = Database::Instance()->get();
				$count = 0;
				$s = "'" . implode("','",$zipCodes) . "'";

				$stmt = $mysqli->prepare("SELECT COUNT(*) AS count FROM `hotspots` WHERE `zipCode` IN (?);");
				$stmt->bind_param("s",$s);
				if($stmt->execute()){
					$result = $stmt->get_result();
					if($result->num_rows){
						$row = $result->fetch_assoc();

						$count = $row["count"];
					}
				}
				$stmt->close();

				return $count;
			} else {
				return 0;
			}
		}
	}

	public static function getTotalResultsFromZipCode($zipCode){
		$n = "totalResults_" . $zipCode;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$c = self::getCitiesFromZipCode($zipCode);
			if(count($c) > 0){
				$p = self::getPlace($zipCode,$c[0]);

				$mysqli = Database::Instance()->get();
				$count = 0;
				$s = "'" . implode("','",$c) . "'";

				$stmt = $mysqli->prepare("SELECT COUNT(*) AS count FROM `hotspots` WHERE `city` IN (?);");
				$stmt->bind_param("s",$s);
				if($stmt->execute()){
					$result = $stmt->get_result();
					if($result->num_rows){
						$row = $result->fetch_assoc();

						$count = $row["count"];
					}
				}
				$stmt->close();

				return $count;
			}
		}
	}

	public static function capitaliseCityName($city){
		$zipCodes = self::getZipCodesFromCity($city);

		if(count($zipCodes) > 0){
			$zipCode = $zipCodes[0];

			foreach(self::getCitiesFromZipCode($zipCode) as $c){
				if(mb_strtolower($city) == mb_strtolower($c)){
					return $c;
				}
			}

			return $city;
		} else {
			return $city;
		}
	}

	private $zipCode;
	private $country;
	private $cityName;
	private $latitude;
	private $longitude;
	private $exists;

	private $totalResults = -1;

	protected function __construct($zipCode,$cityName){
		$cityName = trim($cityName);

		$this->exists = false;
		$this->zipCode = $zipCode;
		$this->cityName = $cityName;
		$mysqli = Database::Instance()->get();

		$stmt = $mysqli->prepare("SELECT * FROM `places` WHERE `code` = ? AND `cityName` = ?");
		$stmt->bind_param("is",$zipCode,$cityName);
		if($stmt->execute()){
			$result = $stmt->get_result();
			if($result->num_rows){
				$this->exists = true;

				$row = $result->fetch_assoc();
				$this->country = $row["country"];
				$this->cityName = $row["cityName"];
				$this->latitude = $row["latitude"];
				$this->longitude = $row["longitude"];

				$this->saveToCache();
			}
		}
	}

	public function getZipCode(){
		return $this->zipCode;
	}

	public function getCountry(){
		return $this->country;
	}

	public function getCountryName(){
		switch($this->country){
			case "DE":
				return "Deutschland";
			case "AT":
				return "Österreich";
			case "CH":
				return "Schweiz";
			default:
				return $this->country;
		}
	}

	public function getCity(){
		return $this->cityName;
	}

	public function getLatLng(){
		if($this->latitude == null || $this->longitude == null){
			$data = self::getGoogleGeocodeData($this->cityName . " " . $this->zipCode);
			if($data != null){
				if(isset($data["results"]) && is_array($data["results"]) && count($data["results"]) > 0){
					foreach($data["results"] as $result){
						$result = $data["results"][0];
						if(isset($result["formatted_address"]) && strpos($result["formatted_address"],$this->getZipCode() . ", " . $this->getCity()) !== -1){
							if(isset($result["geometry"])){
								if(isset($result["geometry"]["location"])){
									$this->latitude = $result["geometry"]["location"]["lat"];
									$this->longitude = $result["geometry"]["location"]["lng"];

									$this->saveLatLng();
									break;
								}
							}
						}
					}
				}
			}
		}

		return ["latitude" => $this->latitude,"longitude" => $this->longitude];
	}

	private function saveLatLng(){
		$mysqli = Database::Instance()->get();

		$stmt = $mysqli->prepare("UPDATE `places` SET `latitude` = ?, `longitude` = ? WHERE (`code` = ? OR `cityName` = ?) AND (`latitude` IS NULL OR `longitude` IS NULL)");
		$stmt->bind_param("ddis",$this->latitude,$this->longitude,$this->zipCode,$this->cityName);
		$stmt->execute();
		$stmt->close();

		$this->saveToCache();
	}

	public function totalResults(){
		if($this->totalResults == -1){
			$mysqli = Database::Instance()->get();

			$stmt = $mysqli->prepare("SELECT COUNT(*) AS count FROM `hotspots` WHERE `zipCode` = ?");
			$stmt->bind_param("i",$this->zipCode);
			if($stmt->execute()){
				$result = $stmt->get_result();
				if($result->num_rows){
					$row = $result->fetch_assoc();

					$this->totalResults = $row["count"];
				}
			}
			$stmt->close();

			$this->saveToCache();
		} else {
			return $this->totalResults;
		}
	}

	public function exists(){
		return $this->exists;
	}

	public function saveToCache(){
		CacheHandler::setToCache("zipCode_" . $this->zipCode . "_" . $this->cityName,$this,20*60);
	}
}