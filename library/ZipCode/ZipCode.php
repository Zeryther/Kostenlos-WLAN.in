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

	public static function getCodeFromCity($city){
		$n = "zipCodeCity_" . $city;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$mysqli = Database::Instance()->get();

			$code = null;
			$stmt = $mysqli->prepare("SELECT * FROM `zipCodes` WHERE `cityName` = ?");
			$stmt->bind_param("s",trim($city));
			if($stmt->execute()){
				$result = $stmt->get_result();
				if($result->num_rows){
					$row = $result->fetch_assoc();

					$code = $row["code"];
				}
			}
			$stmt->close();

			if(!is_null($code)){
				return self::getCode($zipCode);
			} else {
				return null;
			}
		}
	}

	private $zipCode;
	private $country;
	private $cityName;
	private $codeExists;

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

	public function exists(){
		return $this->codeExists;
	}

	public function saveToCache(){
		CacheHandler::setToCache("zipCode_" . $this->zipCode,$this,20*60);
		CacheHandler::setToCache("zipCodeCity_" . $this->cityName,$this,20*60);
	}
}