<?php

define("USERRANK_USER","USER");
define("USERRANK_ADMIN","ADMIN");

class Util {
	public static function isLoggedIn(){
		return isset($_SESSION["id"]);
	}

	public static function getCurrentUser(){
		return self::isLoggedIn() == true ? User::getUserById($_SESSION["id"]) : null;
	}
}