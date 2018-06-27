<?php

$app->bind("/hotspot/add",function(){
    if(!Util::isLoggedIn())
        return $this->reroute("/login");

    $data = [
        "title" => "Einen Hotspot einreichen"
    ];
    
    return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/AddHotspot.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
});