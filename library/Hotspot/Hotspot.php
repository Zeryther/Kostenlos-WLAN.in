<?php

class Hotspot {
	public static function getHotspotById($id){
		$n = "hotspot_" . $id;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$hotspot = new Hotspot($id);
			$hotspot->load();
			if($hotspot->hotspotExists == true){
				return $hotspot;
			} else {
				return null;
			}
		}
	}

	public static function getHotspotFromData($id,$name,$address,$zipCode,$city,$latitude,$longitude,$creator,$creationTime,$valid){
		$hotspot = new Hotspot($id);

		$hotspot->name = $name;
		$hotspot->address = $address;
		$hotspot->zipCode = $zipCode;
		$hotspot->city = $city;
		$hotspot->latitude = $latitude;
		$hotspot->longitude = $longitude;
		$hotspot->creator = $creator;
		$hotspot->valid = $valid;
		$hotspot->creationTime = $creationTime;

		$hotspot->hotspotExists = true;
		$hotspot->saveToCache();

		return $hotspot;
	}

	private $id;
	private $name;
	private $address;
	private $zipCode;
	private $city;
	private $latitude;
	private $longitude;
	private $creator;
	private $valid;
	private $creationTime;

	private $hotspotExists;

	protected function __construct($id){
		$this->hotspotExists = false;
		$this->id = $id;
	}

	private function load(){
		$id = $this->id;
		$mysqli = Database::Instance()->get();
		$stmt = $mysqli->prepare("SELECT * FROM `hotspots` WHERE `id` = ?");
		$stmt->bind_param("i",$id);
		if($stmt->execute()){
			$result = $stmt->get_result();

			if($result->num_rows){
				$this->hotspotExists = true;
				$row = $result->fetch_assoc();

				$this->name = $row["name"];
				$this->address = $row["address"];
				$this->zipCode = $row["zipCode"];
				$this->city = $row["city"];
				$this->latitude = $row["latitude"];
				$this->longitude = $row["longitude"];
				$this->creator = $row["creator"];
				$this->valid = $row["valid"];
				$this->creationTime = $row["time"];

				$this->saveToCache();
			}
		}
		$stmt->close();
	}

	public function getId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getAddress(){
		return $this->address;
	}

	public function getZipCode(){
		return $this->zipCode;
	}

	public function getCity(){
		return $this->city;
	}

	public function getLatitude(){
		return $this->latitude;
	}

	public function getLongitude(){
		return $this->longtitude;
	}

	public function getCreator(){
		return $this->creator;
	}

	public function isValid(){
		return $this->valid;
	}

	public function getCreationTime(){
		return $this->creationTime;
	}

	public function saveToCache(){
		$n = "hotspot_" . $this->id;

		CacheHandler::setToCache($n,$this,20*60);
	}
}