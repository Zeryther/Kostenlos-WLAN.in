<?php

define("USERRANK_USER","USER");
define("USERRANK_ADMIN","ADMIN");

class Util {
	public static function isLoggedIn(){
		return isset($_SESSION["id"]);
	}
}