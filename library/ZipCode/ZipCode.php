<?php

class ZipCode {
	public static function getCode($zipCode){
		$n = "zipCode_" . $zipCode;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$code = new ZipCode($zipCode);
			if($code->codeExists == true){
				return $code;
			} else {
				return null;
			}
		}
	}

	public static function getCodesFromCity($city){
		$n = "zipCodesCity_" . $city;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$mysqli = Database::Instance()->get();

			$codes = array();

			$stmt = $mysqli->prepare("SELECT * FROM `zipCodes` WHERE `cityName` = ?");
			$c = trim($city);
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

	public static function getTotalResultsFromCity($city){
		$n = "totalResults_" . $city;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$zipCodes = self::getCodesFromCity($city);

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

	private $zipCode;
	private $country;
	private $cityName;
	private $codeExists;

	private $totalResults = -1;

	protected function __construct($zipCode){
		$this->codeExists = false;
		$this->zipCode = $zipCode;
		$mysqli = Database::Instance()->get();

		$stmt = $mysqli->prepare("SELECT * FROM `zipCodes` WHERE `code` = ?");
		$stmt->bind_param("i",$zipCode);
		if($stmt->execute()){
			$result = $stmt->get_result();
			if($result->num_rows){
				$this->codeExists = true;

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
		return $this->codeExists;
	}

	public function saveToCache(){
		CacheHandler::setToCache("zipCode_" . $this->zipCode,$this,20*60);
	}
}