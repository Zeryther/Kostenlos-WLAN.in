<?php

namespace KostenlosWLAN;

/**
 * Represents a wi-fi hotspot
 * 
 * @package Hotspot
 * @author Gigadrive (support@gigadrivegroup.com)
 * @copyright 2018 Gigadrive
 * @link https://gigadrivegroup.com/dev/technologies
 */
class Hotspot {
	/**
	 * Get a wi-fi hotspot by ID
	 * 
	 * @access public
	 * @param int $id The hotspot ID
	 * @return Hotspot
	 */
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

	/**
	 * Get a wi-fi hotspot by data
	 * 
	 * @access public
	 * @param int $id
	 * @param string $name
	 * @param string $address
	 * @param int $zipCode
	 * @param string $city
	 * @param float $latitude
	 * @param float $longitude
	 * @param int $creator
	 * @param bool $valid
	 * @param string $googlePlaceId
	 * @param string $photo
	 * @param double $rating
	 * @return Hotspot
	 */
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

	/**
	 * Returns whether a hotspot with the specified data is in the database or not
	 * 
	 * @access public
	 * @param string $googlePlaceId
	 * @param string $address
	 * @param int $zipCode
	 * @param string $city
	 * @return bool
	 */
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

	/**
	 * @access private
	 * @var int $id
	 */
	private $id;

	/**
	 * @access private
	 * @var string $name
	 */
	private $name;

	/**
	 * @access private
	 * @var string $address
	 */
	private $address;

	/**
	 * @access private
	 * @var int $zipCode
	 */
	private $zipCode;

	/**
	 * @access private
	 * @var string $city
	 */
	private $city;

	/**
	 * @access private
	 * @var float $latitude
	 */
	private $latitude;

	/**
	 * @access private
	 * @var float $longitude
	 */
	private $longitude;

	/**
	 * @access private
	 * @var int $creator
	 */
	private $creator;

	/**
	 * @access private
	 * @var bool $valid
	 */
	private $valid;

	/**
	 * @access private
	 * @var string $creationTime
	 */
	private $creationTime;

	/**
	 * @access private
	 * @var string $placeId
	 */
	private $placeId;

	/**
	 * @access private
	 * @var string $photo
	 */
	private $photo;

	/**
	 * @access private
	 * @var double $rating
	 */
	private $rating;

	/**
	 * @access private
	 * @var bool $hotspotExists
	 */
	private $hotspotExists;

	/**
	 * Constructor
	 * 
	 * @access protected
	 * @param int $id
	 */
	protected function __construct($id){
		$this->hotspotExists = false;
		$this->id = $id;
	}

	/**
	 * Loads hotspot data
	 * 
	 * @access private
	 */
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

	/**
	 * Gets the hotspot ID
	 * 
	 * @access public
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Gets the hotspot name
	 * 
	 * @access public
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * Gets the hotspot address
	 * 
	 * @access public
	 * @return string
	 */
	public function getAddress(){
		return $this->address;
	}

	/**
	 * Gets the hotspot's zip code
	 * 
	 * @access public
	 * @return int
	 */
	public function getZipCode(){
		return $this->zipCode;
	}

	/**
	 * Gets the hotspot city
	 * 
	 * @access public
	 * @return string
	 */
	public function getCity(){
		return $this->city;
	}

	/**
	 * Gets the hotspot's latitude
	 * 
	 * @access public
	 * @return float
	 */
	public function getLatitude(){
		return $this->latitude;
	}

	/**
	 * Gets the hotspot's longitude
	 * 
	 * @access public
	 * @return float
	 */
	public function getLongitude(){
		return $this->longitude;
	}

	/**
	 * Gets the hotspot's creator's account ID
	 * 
	 * @access public
	 * @return int
	 */
	public function getCreator(){
		return $this->creator;
	}

	/**
	 * Gets whether the hotspot was marked as valid
	 * 
	 * @access public
	 * @return bool
	 */
	public function isValid(){
		return $this->valid;
	}

	/**
	 * Gets the timestamp at which the hotspot was created
	 * 
	 * @access public
	 * @return string
	 */
	public function getCreationTime(){
		return $this->creationTime;
	}

	/**
	 * Gets the ID of the Place in Google's API
	 * 
	 * @access public
	 * @return string
	 */
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

	/**
	 * Gets the photo ID in Google's API
	 * 
	 * @access public
	 * @return string
	 */
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

	/**
	 * Gets the full URL of the hotspot photo, returns null if there is no photo
	 * 
	 * @access public
	 * @return string
	 */
	public function getPhotoURL(){
		$photo = $this->getPhoto();

		return $photo != null ? "https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference=" . $photo . "&key=" . GOOGLE_MAPS_API_KEY_PRIVATE : null;
	}

	/**
	 * Gets the hotspot rating
	 * 
	 * @access public
	 * @return double
	 */
	public function getRating(){
		return $this->rating;
	}

	/**
	 * Calculates the hotspot rating and updates it
	 * 
	 * @access public
	 */
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

	/**
	 * Gets the hotspot as query to pass on to Google
	 * 
	 * @access public
	 * @return string
	 */
	public function getAsGoogleQuery(){
		return $this->name . " " . $this->address . " " . $this->zipCode . " " . $this->city;
	}

	/**
	 * Gets geocode data from Google's API
	 * 
	 * @access public
	 * @return array|json
	 */
	public function getGoogleGeocodeData(){
		return Place::getGoogleGeocodeData($this->getAsGoogleQuery());
	}

	/**
	 * Gets place data from Google's API
	 * 
	 * @access public
	 * @return array|json
	 */
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

	/**
	 * Marks the hotspot as valid
	 * 
	 * @access public
	 */
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

	/**
	 * Removes the hotspot from the database and the cache
	 * 
	 * @access public
	 */
	public function delete(){
		$mysqli = Database::Instance()->get();

		$stmt = $mysqli->prepare("DELETE FROM `hotspots` WHERE `id` = ?");
		$stmt->bind_param("i",$this->id);
		$stmt->execute();
		$stmt->close();

		CacheHandler::deleteFromCache("hotspot_" . $this->id);
	}

	/**
	 * Saves the hotspot to the cache
	 * 
	 * @access public
	 */
	public function saveToCache(){
		$n = "hotspot_" . $this->id;

		CacheHandler::setToCache($n,$this,20*60);
	}
}