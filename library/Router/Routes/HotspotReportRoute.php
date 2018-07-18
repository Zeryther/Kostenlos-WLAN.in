<?php

use KostenlosWLAN\Util;

$app->bind("/hotspot/report",function(){
    if(!Util::isLoggedIn()) return $this->reroute("/login");

    $data = [
        "title" => "Problem mit einem Hotspot melden",
    ];
    
    return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/HotspotReport.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
});