<?php

class Rating {
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

    private $hotspot;
    private $user;
    private $stars;
    private $comment;
    private $time;

    private $exists = false;

    protected function __construct($hotspot,$user){
        $this->hotspot = $hotspot;
        $this->user = $user;
    }

    private function load(){
        $mysqli = Database::Instance()->get();

        $stmt = $mysqli->prepare("SELECT * FROM `ratings` WHERE `hotspot` = ? AND `user` = ?");
        $stmt->bind_param("ii",$hotspot,$user);
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

    public function getHotspotId(){
        return $this->hotspot;
    }

    public function getHotspot(){
        return Hotspot::getHotspotById($this->hotspot);
    }

    public function getUserId(){
        return $this->user;
    }

    public function getUser(){
        return User::getUserById($this->user);
    }

    public function getStars(){
        return $this->stars;
    }

    public function getComment(){
        return $this->comment;
    }

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

    public function getTime(){
        return $this->time;
    }

    public function saveToCache(){
        $n = "rating_" . $this->hotspot . "_" . $this->user;
        
        CacheHandler::setToCache($n,$this,10*60);
    }
}