<?php

class User {
	public static function getUserById($id){
		$n = "user_" . $id;

		if(CacheHandler::existsInCache($n)){
			return CacheHandler::getFromCache($n);
		} else {
			$user = new User($id);
			if($user->userExists == true){
				return $user;
			} else {
				return null;
			}
		}
	}

	public static function registerAccount($id,$username,$email,$token){
		$mysqli = Database::Instance()->get();
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

	private $id;
	private $username;
	private $email;
	private $token;
	private $level;
	private $registerDate;
	
	private $userExists;

	protected function __construct($id){
		$this->userExists = false;

		$this->id = $id;
		$mysqli = Database::Instance()->get();
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

	public function getId(){
		return $this->id;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getToken(){
		return $this->token;
	}

	public function setToken($token){
		$this->token = $token;
	}

	public function getLevel(){
		return $this->level;
	}

	public function getRegisterDate(){
		return $this->registerDate;
	}

	public function exists(){
		return $this->userExists;
	}

	public function hasOpenReport($hotspot){
		$status = REPORT_STATUS_OPEN;

		$b = false;
		$mysqli = Database::Instance()->get();

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

	public function saveToCache(){
		$n = "user_" . $this->id;

		CacheHandler::setToCache($n,$this,20*60);
	}
}