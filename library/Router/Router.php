<?php

$app = new Lime\App();
$app['config.begin_point'] = microtime();
$app['config.site'] = array(
    "name" => "Kostenlos-Wlan.in"
);

$app->path("assets",$_SERVER["DOCUMENT_ROOT"] . "/assets");

require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Routes/HomeRoute.php";

$app->run();