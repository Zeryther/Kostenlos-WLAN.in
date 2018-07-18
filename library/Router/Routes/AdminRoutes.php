<?php

use KostenlosWLAN\Hotspot;
use KostenlosWLAN\Report;
use KostenlosWLAN\Util;

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
                    $h = Hotspot::getHotspotFromData($row["id"],$row["name"],$row["address"],$row["zipCode"],$row["city"],$row["latitude"],$row["longitude"],$row["creator"],$row["time"],$row["valid"],$row["googlePlaceId"],$row["photo"],$row["rating"]);

                    array_push($results,$h);
                }
            }
            $s->close();
        }

		$data = [
			"title" => "Ausstehende Hotspots - Seite " . $page,
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

$app->bind("/admin/reports",function(){
    return $this->reroute("/admin/reports/1");
});

$app->bind("/admin/reports/:page",function($params){
    $page = (isset($params["page"]) && !empty($params["page"]) && is_numeric($params["page"]) && (int)$params["page"] > 0) ? (int)$params["page"] : 1;

    if(Util::isLoggedIn()){
        if(Util::getCurrentUser()->getLevel() != "ADMIN")
            return $this->reroute("/account");

        $successMsg = null;
        $errorMsg = null;

        if(isset($_POST["action"]) && !empty($_POST["action"])){
            if($_POST["action"] == "accept"){
                if(isset($_POST["reportId"]) && !empty($_POST["reportId"]) && is_numeric($_POST["reportId"])){
                    $report = Report::getReport($_POST["reportId"]);
                    $report->setStatus(REPORT_STATUS_ACCEPTED);
                    $hotspot = $report->getHotspot();

                    $hotspot->delete();

                    $successMsg = "Die Meldung wurde angenommen und der Hotspot wurde aus der Datenbank entfernt.";
                }
            } else if($_POST["action"] == "decline"){
                if(isset($_POST["reportId"]) && !empty($_POST["reportId"]) && is_numeric($_POST["reportId"])){
                    $report = Report::getReport($_POST["reportId"]);
                    $report->setStatus(REPORT_STATUS_DENIED);
                    $hotspot = $report->getHotspot();

                    $successMsg = "Die Meldung wurde abgelehnt.";
                }
            }
        }

        $itemsPerPage = 10;
        $mysqli = Database::Instance()->get();

        $num = 0;

		$s = $mysqli->prepare("SELECT COUNT(`id`) AS `count` FROM `reports` WHERE `status` = 'OPEN'");
		if($s->execute()){
			$result = $s->get_result();

            $num = $result->num_rows ? $result->fetch_assoc()["count"] : 0;
		}
        $s->close();
        
        $results = [];

        if($num > 0){
            $s = $mysqli->prepare("SELECT * FROM `reports` WHERE `status` = 'OPEN' LIMIT " . (($page-1)*$itemsPerPage) . " , " . $itemsPerPage);
            if($s->execute()){
                $result = $s->get_result();

                while($row = $result->fetch_assoc()){
                    $r = Report::getReportFromData($row["id"],$row["user"],$row["hotspot"],$row["reason"],$row["text"],$row["time"],$row["status"]);

                    array_push($results,$r);
                }
            }
            $s->close();
        }

		$data = [
			"title" => "Offene Meldungen - Seite " . $page,
			"printAccountNav" => true,
            "accountNav" => ADMIN_NAV_OPEN_REPORTS,
            "num" => $num,
			"results" => $results,
			"page" => $page,
            "itemsPerPage" => $itemsPerPage,
            "successMsg" => $successMsg,
            "errorMsg" => $errorMsg
		];
	
		return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Admin/OpenReports.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
	} else {
		return $this->reroute("/login");
	}
});