<?php

$app->bind("/sitemap",function(){
    $this->response->mime = "xml";
    $this->response->code = "200"; 

    $n = "sitemapString" . isset($_GET["rand"]) ? "_" . $_GET["rand"] : "";

    $s = "";
    if(CacheHandler::existsInCache($n)){
        $s = CacheHandler::getFromCache($n);
    } else {
        $mysqli = Database::Instance()->get();
        $stmt = $mysqli->prepare("SELECT `cityName` FROM `places` ORDER BY RAND()");
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows){
                while($row = $result->fetch_assoc()){
                    $s .= '<url><loc>https://kostenlos-wlan.in/' . $row["cityName"] . '</loc><changefreq>daily</changefreq><priority>0.60</priority></url>';
                }
            }
        }
        $stmt->close();

        $stmt = $mysqli->prepare("SELECT `id` FROM `hotspots` ORDER BY RAND()");
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows){
                while($row = $result->fetch_assoc()){
                    $s .= '<url><loc>https://kostenlos-wlan.in/hotspot/' . $row["id"] . '</loc><changefreq>daily</changefreq><priority>0.60</priority></url>';
                }
            }
        }
        $stmt->close();

        CacheHandler::setToCache($n,$s,20*60);
    }

    return '<?xml version="1.0" encoding="UTF-8" ?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://sitemaps.org/schemas/sitemap/0.9 http://sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url>
    <loc>https://kostenlos-wlan.in/</loc>
    <changefreq>daily</changefreq>
    <priority>1.00</priority>
    </url>' . $s .
    '</urlset>';
});