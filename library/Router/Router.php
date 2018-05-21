<?php

$app = new Lime\App();
$app['config.begin_point'] = microtime();
$app['config.site'] = array(
    "name" => "Kostenlos-WLAN.in"
);

$app->path("assets",$_SERVER["DOCUMENT_ROOT"] . "/assets");

require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Routes/HomeRoute.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Routes/LoginRoute.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Routes/LogoutRoute.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Routes/RegisterRoute.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Routes/LoginCallbackRoute.php";

$app->run();