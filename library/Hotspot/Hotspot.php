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

	public static function getHotspotFromData($id,$name,$address,$zipCode,$city,$latitude,$longitude,$creator,$creationTime,$valid,$googlePlaceId = null,$photo = null,$rating = 0){
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
		$hotspot->placeID = $googlePlaceId;
		$hotspot->photo = $photo;
		$hotspot->rating = $rating;

		$hotspot->hotspotExists = true;
		$hotspot->saveToCache();

		return $hotspot;
	}

	public static function isHotspotInDatabase($googlePlaceId,$address,$zipCode,$city){
		$b = false;

		$mysqli = Database::Instance()->get();

		$stmt = $mysqli->prepare("SELECT COUNT(`id`) AS `count` FROM `hotspots` WHERE `googlePlaceId` = ?");
		$stmt->bind_param("s",$googlePlaceId);
		if($stmt->execute()){
			$result = $stmt->get_result();

			if($result->num_rows){
				$row = $result->fetch_assoc();

				if($row["count"] > 0)
					$b = true;
			}
		}
		$stmt->close();

		if($b)
			return $b;

		$stmt = $mysqli->prepare("SELECT COUNT(`id`) AS `count` FROM `hotspots` WHERE `address` LIKE ? AND `zipCode` = ? AND `city` = ?");
		$stmt->bind_param("sis",$address,$zipCode,$city);
		if($stmt->execute()){
			$result = $stmt->get_result();

			if($result->num_rows){
				$row = $result->fetch_assoc();

				if($row["count"] > 0)
					$b = true;
			}
		}
		$stmt->close();

		return $b;
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
	private $placeId;
	private $photo;
	private $rating;

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
				$this->placeId = $row["googlePlaceId"];
				$this->photo = $row["photo"];
				$this->rating = $row["rating"];

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
		return $this->longitude;
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

	public function getGooglePlaceID(){
		if($this->placeId == null){
			$data = Place::getGoogleGeocodeData($this->getName() . " " . $this->getAddress() . " " . $this->getZipCode() . " " . $this->getCity());

			if(isset($data["results"]) && is_array($data["results"]) && count($data["results"]) > 0){
				$result = $data["results"][0];

				if(isset($result["place_id"])){
					$this->placeId = $result["place_id"];

					$mysqli = Database::Instance()->get();
					$stmt = $mysqli->prepare("UPDATE `hotspots` SET `googlePlaceId` = ? WHERE `id` = ?");
					$stmt->bind_param("si",$this->placeId,$this->id);
					$stmt->execute();
					$stmt->close();

					$this->saveToCache();
				}
			}
		}

		return $this->placeId;
	}

	public function getPhoto(){
		if($this->photo == null){
			$googlePlaceData = $this->getGooglePlaceData();

			if($googlePlaceData != null){
				if(isset($googlePlaceData["result"])){
					$result = $googlePlaceData["result"];

					if(isset($result["photos"]) && is_array($result["photos"]) && count($result["photos"]) > 0){
						$this->photo = $result["photos"][0]["photo_reference"];

						$mysqli = Database::Instance()->get();
						$stmt = $mysqli->prepare("UPDATE `hotspots` SET `photo` = ? WHERE `id` = ?");
						$stmt->bind_param("si",$this->photo,$this->id);
						$stmt->execute();
						$stmt->close();

						$this->saveToCache();
					}
				}
			}
		}

		return $this->photo;
	}

	public function getPhotoURL(){
		$photo = $this->getPhoto();

		return $photo != null ? "https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference=" . $photo . "&key=" . GOOGLE_MAPS_API_KEY_PRIVATE : null;
	}

	public function getRating(){
		return $this->rating;
	}

	public function updateRating(){
		$mysqli = Database::Instance()->get();

		$rating = $this->rating;

		$stmt = $mysqli->prepare("SELECT SUM(`stars`)/COUNT(`stars`) AS `rating` FROM `ratings` WHERE `hotspot` = ?");
		$stmt->bind_param("i",$this->id);
		if($stmt->execute()){
			$result = $stmt->get_result();

			if($result->num_rows){
				$row = $result->fetch_assoc();

				$rating = (double)$row["rating"];
			}
		}
		$stmt->close();

		$stmt = $mysqli->prepare("UPDATE `hotspots` SET `rating` = ? WHERE `id` = ?");
		$stmt->bind_param("di",$rating,$this->id);
		$stmt->execute();
		$stmt->close();

		$this->rating = $rating;
		$this->saveToCache();
	}

	public function getAsGoogleQuery(){
		return $this->name . " " . $this->address . " " . $this->zipCode . " " . $this->city;
	}

	public function getGoogleGeocodeData(){
		return Place::getGoogleGeocodeData($this->getAsGoogleQuery());
	}

	public function getGooglePlaceData(){
		$placeId = $this->getGooglePlaceID();

		if($placeId != null){
			$url = "https://maps.googleapis.com/maps/api/place/details/json?key=" . GOOGLE_MAPS_API_KEY_PRIVATE . "&placeid=" . $placeId;

			$json = @json_decode(@file_get_contents($url),true);

			return $json;
		} else {
			return null;
		}
	}

	public function accept(){
		if($this->valid == true) return;

		$this->valid = true;

		$mysqli = Database::Instance()->get();
		$stmt = $mysqli->prepare("UPDATE `hotspots` SET `valid` = 1 WHERE `id` = ?");
		$stmt->bind_param("i",$this->id);
		$stmt->execute();
		$stmt->close();

		$this->saveToCache();
	}

	public function delete(){
		$mysqli = Database::Instance()->get();

		$stmt = $mysqli->prepare("DELETE FROM `hotspots` WHERE `id` = ?");
		$stmt->bind_param("i",$this->id);
		$stmt->execute();
		$stmt->close();

		CacheHandler::deleteFromCache("hotspot_" . $this->id);
	}

	public function saveToCache(){
		$n = "hotspot_" . $this->id;

		CacheHandler::setToCache($n,$this,20*60);
	}
}