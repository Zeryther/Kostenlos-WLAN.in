<?php

$app->bind("/hotspot/:id",function($params){
	$id = $params["id"];

	if(is_numeric($id)){
		$hotspot = Hotspot::getHotspotById($id);

		if($hotspot != null){
			$data = [
				"title" => $hotspot->getName() . " Hotspot in " . $hotspot->getCity(),
				"hotspot" => $hotspot
			];
			
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Hotspot.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		} else {
			$this->reroute("/?msg=unknownHotspot");	
		}
	} else {
		$this->reroute("/?msg=unknownHotspot");
	}
});