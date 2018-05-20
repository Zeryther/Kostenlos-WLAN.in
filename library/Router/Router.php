<?php

$app = new Lime\App();
$app['config.begin_point'] = microtime();
$app['config.site'] = array(
    "name" => "Kostenlos-Wlan.in"
);

$app->path("assets",$_SERVER["DOCUMENT_ROOT"] . "/assets");
