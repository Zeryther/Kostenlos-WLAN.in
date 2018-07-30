<?php

use KostenlosWLAN\Hotspot;

$app->bind("/api/mapsScript",function(){
    $content = "";

    $this->response->mime = "javascript";

    $n = "mapsScript";
    if(CacheHandler::existsInCache($n)){
        $content = CacheHandler::getFromCache($n);
    } else {
        $url = "https://maps.googleapis.com/maps/api/js?key=" . GOOGLE_MAPS_API_KEY_PUBLIC . "&callback=initMap";

        $content = file_get_contents($url);

        CacheHandler::setToCache($n,$content,2*60*60);
    }
    
    return $content;
});