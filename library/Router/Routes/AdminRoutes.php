<?php

$app->bind("/admin/pendingSpots",function(){
    return $this->reroute("/admin/pendingSpots/1");
});

$app->bind("/admin/pendingSpots/:page",function($params){
    $page = (isset($params["page"]) && !empty($params["page"]) && is_numeric($params["page"]) && (int)$params["page"] > 0) ? (int)$params["page"] : 1;

    if(Util::isLoggedIn()){
        if(Util::getCurrentUser()->getLevel() != "ADMIN")
            return $this->reroute("/account");

        $successMsg = null;
        $errorMsg = null;

        if(isset($_POST["action"]) && !empty($_POST["action"])){
            if($_POST["action"] == "accept"){
                if(isset($_POST["hotspotId"]) && !empty($_POST["hotspotId"]) && is_numeric($_POST["hotspotId"])){
                    $hotspot = Hotspot::getHotspotById((int)$_POST["hotspotId"]);
            
                    $hotspot->accept();
                    $successMsg = "Der Hotspot wurde erfolgreich verifiziert.";
                }
            } else if($_POST["action"] == "decline"){
                if(isset($_POST["hotspotId"]) && !empty($_POST["hotspotId"]) && is_numeric($_POST["hotspotId"])){
                    $hotspot = Hotspot::getHotspotById((int)$_POST["hotspotId"]);
            
                    $hotspot->delete();
                    $errorMsg = "Der Hotspot wurde erfolgreich entfernt.";
                }
            }
        }

        $itemsPerPage = 10;
        $mysqli = Database::Instance()->get();

        $num = 0;

		$s = $mysqli->prepare("SELECT COUNT(`id`) AS `count` FROM `hotspots` WHERE `valid` = 0");
		if($s->execute()){
			$result = $s->get_result();

            $num = $result->num_rows ? $result->fetch_assoc()["count"] : 0;
		}
        $s->close();
        
        $results = [];

        if($num > 0){
            $s = $mysqli->prepare("SELECT * FROM `hotspots` WHERE `valid` = 0 LIMIT " . (($page-1)*$itemsPerPage) . " , " . $itemsPerPage);
            if($s->execute()){
                $result = $s->get_result();

                while($row = $result->fetch_assoc()){
                    $h = Hotspot::getHotspotFromData($row["id"],$row["name"],$row["address"],$row["zipCode"],$row["city"],$row["latitude"],$row["longitude"],$row["creator"],$row["time"],$row["valid"],$row["googlePlaceId"]);

                    array_push($results,$h);
                }
            }
            $s->close();
        }

		$data = [
			"title" => "Ausstehende Hotspots - Seite " . $num,
			"printAccountNav" => true,
            "accountNav" => ADMIN_NAV_PENDING_SPOTS,
            "num" => $num,
			"results" => $results,
			"page" => $page,
            "itemsPerPage" => $itemsPerPage,
            "successMsg" => $successMsg,
            "errorMsg" => $errorMsg
		];
	
		return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Admin/PendingHotspots.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
	} else {
		return $this->reroute("/login");
	}
});