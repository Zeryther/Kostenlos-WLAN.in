<?php

namespace KostenlosWLAN;

/**
 * Represents a rating given by a user to a hotspot
 * 
 * @package Rating
 * @author Gigadrive (support@gigadrivegroup.com)
 * @copyright 2018 Gigadrive
 * @link https://gigadrivegroup.com/dev/technologies
 */
class Rating {
	/**
	 * Get a rating by hotspot and user IDs
	 * 
	 * @access public
	 * @param int $hotspot
	 * @param int $user
	 * @return Rating
	 */
    public static function getRating($hotspot,$user){
        $n = "rating_" . $hotspot . "_" . $user;

        if(CacheHandler::existsInCache($n)){
            return CacheHandler::getFromCache($n);
        } else {
            $rating = new Rating($hotspot,$user);
            $rating->load();

            if($rating->exists == true){
                return $rating;
            } else {
                return null;
            }
        }
    }

	/**
	 * Gets a rating from data
	 * 
	 * @access public
	 * @param int $hotspot
	 * @param int $user
	 * @param double $stars
	 * @param string $comment
	 * @param string $time
	 * @return Rating
	 */
    public static function getRatingFromData($hotspot,$user,$stars,$comment,$time){
        $rating = new Rating($hotspot,$user);

        $rating->hotspot = $hotspot;
        $rating->user = $user;
        $rating->stars = $stars;
        $rating->comment = $comment;
        $rating->time = $time;
        $rating->exists = true;

        $rating->saveToCache();

        return $rating;
    }

	/**
	 * Creates a rating and saves it to the database
	 * 
	 * @access public
	 * @param int $hotspot
	 * @param int $user
	 * @param double $stars
	 * @param string $comment
	 * @return Rating The rating object, returns null if the rating could not be created
	 */
    public static function createRating($hotspot,$user,$stars,$comment){
        $b = false;

        $mysqli = Database::Instance()->get();
        $stmt = $mysqli->prepare("INSERT INTO `ratings` (`hotspot`,`user`,`stars`,`comment`) VALUES(?,?,?,?);");
        $stmt->bind_param("iids",$hotspot,$user,$stars,$comment);
        if($stmt->execute())
            $b = true;
        $stmt->close();

        return $b == true ? Rating::getRating($hotspot,$user) : null;
    }

	/**
	 * @access private
	 * @var int $hotspot
	 */
	private $hotspot;
	
	/**
	 * @access private
	 * @var int $user
	 */
	private $user;
	
	/**
	 * @access private
	 * @var double $stars
	 */
	private $stars;
	
	/**
	 * @access private
	 * @var string $comment
	 */
	private $comment;
	
	/**
	 * @access private
	 * @var string $time
	 */
    private $time;

	/**
	 * @access private
	 * @var bool $exists
	 */
    private $exists = false;

	/**
	 * Constructor
	 * 
	 * @access protected
	 * @param int $hotspot
	 * @param int $user
	 */
    protected function __construct($hotspot,$user){
        $this->hotspot = $hotspot;
        $this->user = $user;
    }

	/**
	 * Loads the rating data
	 * 
	 * @access private
	 */
    private function load(){
        $mysqli = Database::Instance()->get();

        $stmt = $mysqli->prepare("SELECT * FROM `ratings` WHERE `hotspot` = ? AND `user` = ?");
        $stmt->bind_param("ii",$this->hotspot,$this->user);
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows){
                $row = $result->fetch_assoc();

                $this->hotspot = $row["hotspot"];
                $this->user = $row["user"];
                $this->stars = $row["stars"];
                $this->comment = $row["comment"];
                $this->time = $row["time"];

                $this->exists = true;
                $this->saveToCache();
            }
        }
        $stmt->close();
    }

	/**
	 * Gets the hotspot id
	 * 
	 * @access public
	 * @return int
	 */
    public function getHotspotId(){
        return $this->hotspot;
    }

	/**
	 * Gets the hotspot object
	 * 
	 * @access public
	 * @return Hotspot
	 */
    public function getHotspot(){
        return Hotspot::getHotspotById($this->hotspot);
    }

	/**
	 * Gets the user id
	 * 
	 * @access public
	 * @return int
	 */
    public function getUserId(){
        return $this->user;
    }

	/**
	 * Gets the user object
	 * 
	 * @access public
	 * @return User
	 */
    public function getUser(){
        return User::getUserById($this->user);
    }

	/**
	 * Gets the amount of stars given
	 * 
	 * @access public
	 * @return double
	 */
    public function getStars(){
        return $this->stars;
    }

	/**
	 * Gets the comment written
	 * 
	 * @access public
	 * @return string
	 */
    public function getComment(){
        return $this->comment;
    }

	/**
	 * Updates the rating's stars and comment
	 * 
	 * @access public
	 * @param double $stars
	 * @param string $comment
	 */
    public function update($stars,$comment){
        if($stars == $this->stars && $comment == $this->comment) return;

        $this->stars = $stars;
        $this->comment = $comment;

        $mysqli = Database::Instance()->get();
        $stmt = $mysqli->prepare("UPDATE `ratings` SET `stars` = ?, `comment` = ? WHERE `hotspot` = ? AND `user` = ?");
        $stmt->bind_param("dsii",$stars,$comment,$this->hotspot,$this->user);
        $stmt->execute();
        $stmt->close();

        $this->saveToCache();
    }

	/**
	 * Gets the timestamp when the rating was created
	 * 
	 * @access public
	 * @return string
	 */
    public function getTime(){
        return $this->time;
    }

	/**
	 * Saves the rating to the cache
	 * 
	 * @access public
	 */
    public function saveToCache(){
        $n = "rating_" . $this->hotspot . "_" . $this->user;
        
        CacheHandler::setToCache($n,$this,10*60);
    }
}