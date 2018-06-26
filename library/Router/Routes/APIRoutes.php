<?php

$app->bind("/api/spots",function(){
    $this->response->mime = "json";

    $results = [];

    $n = "api_spots";
    if(CacheHandler::existsInCache($n)){
        $results = CacheHandler::getFromCache($n);
    } else {
        $mysqli = Database::Instance()->get();

        $stmt = $mysqli->prepare("SELECT * FROM `hotspots` WHERE `valid` = 1");
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows){
                while($row = $result->fetch_assoc()){
                    $hotspot = Hotspot::getHotspotFromData($row["id"],$row["name"],$row["address"],$row["zipCode"],$row["city"],$row["latitude"],$row["longitude"],$row["creator"],$row["time"],$row["valid"],$row["googlePlaceId"]);

                    $h = [
                        "id" => $row["id"],
                        "name" => $row["name"],
                        "address" => $row["address"],
                        "zipCode" => $row["zipCode"],
                        "city" => $row["city"],
                        "latitude" => $row["latitude"],
                        "longitude"=> $row["longitude"]
                    ];

                    array_push($results,$h);
                }
            }

            CacheHandler::setToCache($n,$results,10*60);
        }
        $stmt->close();
    }

    return json_encode($results);
});