<?php

session_start();

if(isset($_SERVER["HTTP_HOST"]) && (explode(":",$_SERVER["HTTP_HOST"])[0] == "localhost" || explode(":",$_SERVER["HTTP_HOST"])[0] == "127.0.0.1")){
	require_once $_SERVER["DOCUMENT_ROOT"] . "/../kostenloswlan-config.php";
} else {
	require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Database/Database.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Lime/App.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Handler/Shutdown.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Util/Util.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/User/User.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Hotspot/Hotspot.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Cache/CacheHandler.php";
