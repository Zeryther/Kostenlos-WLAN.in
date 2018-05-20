<?php

class Hotspot {
	public static function getHotspotById($id){
		$n = "hotspot_" . $id;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$hotspot = new Hotspot($id);
			if($hotspot->hotspotExists == true){
				return $hotspot;
			} else {
				return null;
			}
		}
	}

	private $id;
	private $name;
	private $address;
	private $latitude;
	private $longtitude;
	private $creator;
	private $creationTime;

	private $hotspotExists;

	protected function __construct($id){
		$this->hotspotExists = false;
		$this->id = $id;

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
				$this->latitude = $row["latitude"];
				$this->longtitude = $row["longtitude"];
				$this->creator = $row["creator"];
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

	public function getLatitude(){
		return $this->latitude;
	}

	public function getLongtitude(){
		return $this->longtitude;
	}

	public function getCreator(){
		return $this->creator;
	}

	public function getCreationTime(){
		return $this->creationTime;
	}

	public function saveToCache(){
		$n = "hotspot_" . $this->id;

		CacheHandler::setToCache($n,$this,20*60);
	}
}