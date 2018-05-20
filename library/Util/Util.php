<?php

class Util {
	public static function isLoggedIn(){
		return isset($_SESSION["id"]);
	}
}