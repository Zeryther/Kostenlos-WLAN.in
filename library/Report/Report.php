<?php

class Report {
    public static function getReport($id){
        $n = "report_" . $id;

        if(CacheHandler::existsInCache($n)){
            return CacheHandler::getFromCache($n);
        } else {
            $report = new Report($id);
            $report->load();

            if($report->exists == true){
                return $report;
            } else {
                return null;
            }
        }
    }

    public static function getReportFromData($id,$user,$hotspot,$reason,$text,$time,$status){
        $report = new Report($id);

        $report->id = $id;
        $report->user = $user;
        $report->hotspot = $hotspot;
        $report->reason = $reason;
        $report->text = $text;
        $report->time = $time;
        $report->status = $status;
        $report->exists = true;

        $report->saveToCache();

        return $report;
    }

    public static function createReport($user,$hotspot,$reason,$text){
        $report = null;
        $mysqli = Database::Instance()->get();

        $stmt = $mysqli->prepare("INSERT INTO `reports` (`user`,`hotspot`,`reason`,`text`) VALUES(?,?,?,?);");
        $stmt->bind_param("iiss",$user,$hotspot,$reason,$text);
        if($stmt->execute()){
            $report = self::getReport($stmt->insert_id);
        }
        $stmt->close();

        return $report;
    }

    private $id;
    private $user;
    private $hotspot;
    private $reason;
    private $text;
    private $time;
    private $status;

    private $exists = false;

    protected function __construct($id){
        $this->id = $id;
    }

    private function load(){
        $mysqli = Database::Instance()->get();

        $stmt = $mysqli->prepare("SELECT * FROM `reports` WHERE `id` = ?");
        $stmt->bind_param("i",$this->id);
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows){
                $row = $result->fetch_assoc();

                $this->id = $row["id"];
                $this->user = $row["user"];
                $this->hotspot = $row["hotspot"];
                $this->reason = $row["reason"];
                $this->text = $row["text"];
                $this->time = $row["time"];
                $this->status = $row["status"];

                $this->exists = true;
                $this->saveToCache();
            }
        }
        $stmt->close();
    }

    public function getId(){
        return $this->id;
    }

    public function getUserId(){
        return $this->user;
    }

    public function getUser(){
        return User::getUserById($this->user);
    }

    public function getHotspotId(){
        return $this->hotspot;
    }

    public function getHotspot(){
        return Hotspot::getHotspotById($this->hotspot);
    }

    public function getReason(){
        return $this->reason;
    }

    public function getText(){
        return $this->text;
    }

    public function getTime(){
        return $this->time;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status){
        $this->status = $status;

        $mysqli = Database::Instance()->get();

        $stmt = $mysqli->prepare("UPDATE `reports` SET `status` = ? WHERE `id` = ?");
        $stmt->bind_param("si",$this->status,$this->id);
        $stmt->execute();
        $stmt->close();

        $this->saveToCache();
    }

    public function exists(){
        return $this->exists;
    }

    public function saveToCache(){
        $n = "report_" . $this->id;

        CacheHandler::setToCache($n,$this,10*60);
    }
}