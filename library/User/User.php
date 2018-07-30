<?php

namespace KostenlosWLAN;

/**
 * Represents a user that was previously authenticated by Gigadrive
 * 
 * @package User
 * @author Gigadrive (support@gigadrivegroup.com)
 * @copyright 2018 Gigadrive
 * @link https://gigadrivegroup.com/dev/technologies
 */
class User {
	/**
	 * Get a user by ID
	 * 
	 * @access public
	 * @param int $id
	 * @return User
	 */
	public static function getUserById($id){
		$n = "user_" . $id;

		if(\CacheHandler::existsInCache($n)){
			return \CacheHandler::getFromCache($n);
		} else {
			$user = new User($id);
			if($user->userExists == true){
				return $user;
			} else {
				return null;
			}
		}
	}

	/**
	 * Save a user's data to the database and register him if needed
	 * 
	 * @access public
	 * @param int $id
	 * @param string $username
	 * @param string $email
	 * @param string $token
	 */
	public static function registerAccount($id,$username,$email,$token){
		$mysqli = \Database::Instance()->get();
		$account = User::getUserById($id);

		if($account == null){
			$stmt = $mysqli->prepare("INSERT IGNORE INTO `users` (`id`,`username`,`email`,`token`) VALUES(?,?,?,?);");
			$stmt->bind_param("isss",$id,$username,$email,$token);
			$stmt->execute();
			$stmt->close();

			User::getUserById($id); // cache data after registering
		} else {
			$stmt = $mysqli->prepare("UPDATE `users` SET `username` = ?, `email` = ?, `token` = ? WHERE `id` = ?");
			$stmt->bind_param("sssi",$username,$email,$token,$id);
			$stmt->execute();
			$stmt->close();

			$account->setUsername($username);
			$account->setEmail($email);
			$account->setToken($token);
			$account->saveToCache();
		}
	}

	/**
	 * @access private
	 * @var int $id
	 */
	private $id;

	/**
	 * @access private
	 * @var string $username
	 */
	private $username;

	/**
	 * @access private
	 * @var string $email
	 */
	private $email;

	/**
	 * @access private
	 * @var string $token
	 */
	private $token;

	/**
	 * @access private
	 * @var string $level
	 */
	private $level;

	/**
	 * @access private
	 * @var string $registerDate
	 */
	private $registerDate;
	
	/**
	 * @access private
	 * @var bool $userExists
	 */
	private $userExists;

	/**
	 * Constructor
	 * 
	 * @access protected
	 * @param int $id
	 */
	protected function __construct($id){
		$this->userExists = false;

		$this->id = $id;
		$mysqli = \Database::Instance()->get();
		$stmt = $mysqli->prepare("SELECT * FROM `users` WHERE `id` = ?");
		$stmt->bind_param("i",$id);
		if($stmt->execute()){
			$result = $stmt->get_result();

			if($result->num_rows){
				$this->userExists = true;
				$row = $result->fetch_assoc();

				$this->username = $row["username"];
				$this->email = $row["email"];
				$this->token = $row["token"];
				$this->level = $row["level"];
				$this->registerDate = $row["time"];

				$this->saveToCache();
			}
		}
		$stmt->close();
	}

	/**
	 * Get the account's ID
	 * 
	 * @access public
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Get the account's username
	 * 
	 * @access public
	 * @return string
	 */
	public function getUsername(){
		return $this->username;
	}

	/**
	 * @access public
	 * @param string $username
	 */
	public function setUsername($username){
		$this->username = $username;
	}

	/**
	 * Get the account's email address
	 * 
	 * @access public
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * @access public
	 * @param string $email
	 */
	public function setEmail($email){
		$this->email = $email;
	}

	/**
	 * Get the account's Gigadrive API token
	 * 
	 * @access public
	 * @return string
	 */
	public function getToken(){
		return $this->token;
	}

	/**
	 * @access public
	 * @param string $token
	 */
	public function setToken($token){
		$this->token = $token;
	}

	/**
	 * Get the account's user level that defines what the user has access to
	 * 
	 * @access public
	 * @return string
	 */
	public function getLevel(){
		return $this->level;
	}

	/**
	 * Get the user's registration date
	 * 
	 * @access public
	 * @return string
	 */
	public function getRegisterDate(){
		return $this->registerDate;
	}

	/**
	 * Gets whether the user is valid and exists
	 * 
	 * @access public
	 * @return string
	 */
	public function exists(){
		return $this->userExists;
	}

	/**
	 * Gets whether the user has an open report for a specific hotspot
	 * 
	 * @access public
	 * @param int $hotspot
	 * @return bool
	 */
	public function hasOpenReport($hotspot){
		$status = REPORT_STATUS_OPEN;

		$b = false;
		$mysqli = \Database::Instance()->get();

		$stmt = $mysqli->prepare("SELECT COUNT(`id`) AS `count` FROM `reports` WHERE `user` = ? AND `hotspot` = ? AND `status` = ?");
		$stmt->bind_param("iis",$this->id,$hotspot,$status);
		if($stmt->execute()){
			$result = $stmt->get_result();

			if($result->num_rows){
				$row = $result->fetch_assoc();

				if($row["count"] > 0){
					$b = true;
				}
			}
		}
		$stmt->close();

		return $b;
	}

	/**
	 * Saves the user to the cache
	 * 
	 * @access public
	 */
	public function saveToCache(){
		$n = "user_" . $this->id;

		\CacheHandler::setToCache($n,$this,20*60);
	}
}