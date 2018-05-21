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
						array_push($codes,$row["code"]);
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

			$stmt = $mysqli->prepare("SELECT `cityName` FROM `places` WHERE `code` = ?");
			$stmt->bind_param("i",$code);
			if($stmt->execute()){
				$result = $stmt->get_result();
				if($result->num_rows){
					while($row = $result->fetch_assoc()){
						array_push($cities,$row["cityName"]);
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