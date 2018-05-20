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

	private $id;
	private $email;
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

				$this->email = $row["email"];
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

	public function getEmail(){
		return $this->email;
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

	public function saveToCache(){
		$n = "user_" . $this->id;

		CacheHandler::setToCache($n,$this,20*60);
	}
}