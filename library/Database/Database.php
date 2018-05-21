<?php

class Database {
	private $db = null;
	private $tried = false;
	private $connected = false;

	public static function Instance(){
		static $inst = null;
		if($inst == null){
			$inst = new self(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DATABASE,MYSQL_PORT);
		}

		return $inst;
	}

	protected function __construct($host,$user,$password,$database,$port = 3306){
		if($this->tried == false){
			$this->tried = true;
			$this->db = @new mysqli($host,$user,$password,$database,$port);
			$this->db->set_charset("utf8mb4");
			mysqli_report(MYSQLI_REPORT_ERROR);

			if($this->db->connect_error){
				die("Connect Error: " . $this->db->connect_error);
			} else {
				$connected = true;
			}
		}
	}

	public function __destruct(){
		$this->shutdown();
	}

	public function get(){
		return $this->db;
	}

	public function connected(){
		return $this->connected;
	}

	public function fetchAll(){
		if(func_num_args() >= 1){
			$query = func_get_arg(0);
			$arguments = array();

			for($i = 0; $i < func_num_args(); $i++){
				if($i == 0) continue;
				array_push($arguments,func_get_arg($i));
			}

			// TODO			
		} else {
			throw new InvalidArgumentException("Missing query");
		}
	}

	public function shutdown(){
		if($this->connected == true){
			$this->connected = false;

			if(self::$instance == $this){
				self::$instance = null;
			}

			return $this->db->close();
		} else {
			return true;
		}
	}
}
Database::Instance();