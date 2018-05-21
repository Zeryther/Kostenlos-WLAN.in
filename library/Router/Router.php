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
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Routes/SearchRoute.php";

$app->on("after",function() {
	if($this->response->status == "404"){
		$data = array(
			"title" => "404: Seite nicht gefunden"
		);

		$this->response->body = $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/ErrorPages/404.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
	}
});

$app->run();