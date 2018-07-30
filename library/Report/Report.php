<?php

namespace KostenlosWLAN;

/**
 * Represents a report made by a user about a hotspot
 * 
 * @package Report
 * @author Gigadrive (support@gigadrivegroup.com)
 * @copyright 2018 Gigadrive
 * @link https://gigadrivegroup.com/dev/technologies
 */
class Report {
	/**
	 * Gets a report by ID
	 * 
	 * @access public
	 * @param int $id
	 * @return Report
	 */
    public static function getReport($id){
        $n = "report_" . $id;

        if(\CacheHandler::existsInCache($n)){
            return \CacheHandler::getFromCache($n);
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

	/**
	 * Gets a report by data
	 * 
	 * @access public
	 * @param int $id
	 * @param int $user
	 * @param int $hotspot
	 * @param string $reason
	 * @param string $text
	 * @param string $time
	 * @param string $status
	 * @return Report
	 */
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

	/**
	 * Creates a report and saves it to the database
	 * 
	 * @access public
	 * @param int $user
	 * @param int $hotspot
	 * @param string $reason
	 * @param string $text
	 * @return Report
	 */
    public static function createReport($user,$hotspot,$reason,$text){
        $report = null;
        $mysqli = \Database::Instance()->get();

        $stmt = $mysqli->prepare("INSERT INTO `reports` (`user`,`hotspot`,`reason`,`text`) VALUES(?,?,?,?);");
        $stmt->bind_param("iiss",$user,$hotspot,$reason,$text);
        if($stmt->execute()){
            $report = self::getReport($stmt->insert_id);
        }
        $stmt->close();

        return $report;
    }

	/**
	 * @access private
	 * @var int $id
	 */
	private $id;
	
	/**
	 * @access private
	 * @var int $user
	 */
	private $user;
	
	/**
	 * @access private
	 * @var int $hotspot
	 */
	private $hotspot;
	
	/**
	 * @access private
	 * @var string $reason
	 */
	private $reason;
	
	/**
	 * @access private
	 * @var string $text
	 */
	private $text;
	
	/**
	 * @access private
	 * @var string $time
	 */
	private $time;
	
	/**
	 * @access private
	 * @var string $status
	 */
    private $status;

	/**
	 * @access private
	 * @var bool $exists
	 */
    private $exists = false;

	/**
	 * Constructor
	 * 
	 * @access protected
	 * @param int $id
	 */
    protected function __construct($id){
        $this->id = $id;
    }

	/**
	 * Loads data about the report
	 * 
	 * @access private
	 */
    private function load(){
        $mysqli = \Database::Instance()->get();

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

	/**
	 * Gets the report id
	 * 
	 * @access public
	 * @return int
	 */
    public function getId(){
        return $this->id;
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
	 * Gets the hotspot id
	 * 
	 * @access public
	 * @return Hotspot
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
	 * Gets the report reason
	 * 
	 * @access public
	 * @return string
	 */
    public function getReason(){
        return $this->reason;
    }

	/**
	 * Gets the report text
	 * 
	 * @access public
	 * @return string
	 */
    public function getText(){
        return $this->text;
    }

	/**
	 * Gets the timestamp when the report was created
	 * 
	 * @access public
	 * @return string
	 */
    public function getTime(){
        return $this->time;
    }

	/**
	 * Gets the report status
	 * 
	 * @access public
	 * @return string
	 */
    public function getStatus(){
        return $this->status;
    }

	/**
	 * Updates the report status
	 * 
	 * @access public
	 * @param string $status
	 */
    public function setStatus($status){
        $this->status = $status;

        $mysqli = \Database::Instance()->get();

        $stmt = $mysqli->prepare("UPDATE `reports` SET `status` = ? WHERE `id` = ?");
        $stmt->bind_param("si",$this->status,$this->id);
        $stmt->execute();
        $stmt->close();

        $this->saveToCache();
    }

	/**
	 * Gets whether the report is valid and exists
	 * 
	 * @access public
	 * @return bool
	 */
    public function exists(){
        return $this->exists;
    }

	/**
	 * Saves the report to the cache
	 * 
	 * @access public
	 */
    public function saveToCache(){
        $n = "report_" . $this->id;

        \CacheHandler::setToCache($n,$this,10*60);
    }
}