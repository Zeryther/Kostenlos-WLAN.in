<?php

$app->bind("/hotspot/:id",function($params){
	$id = $params["id"];

	if(is_numeric($id)){
		$hotspot = Hotspot::getHotspotById($id);

		if($hotspot != null){
			$twitterImage = $hotspot->getPhoto() != null ? $hotspot->getPhotoUrl() : DEFAULT_TWITTER_IMAGE;

			$data = [
				"title" => $hotspot->getName() . " Hotspot in " . $hotspot->getCity(),
				"hotspot" => $hotspot,
				"lat" => $hotspot->getLatitude(),
				"lng" => $hotspot->getLongitude(),
				"twitterImage" => $twitterImage,
				"markerHtml" => "<b>" . $hotspot->getName() . "</b><br/>" . $hotspot->getAddress() . "<br/>" . $hotspot->getZipCode() . " " . $hotspot->getCity()
			];
			
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Hotspot.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		} else {
			$this->reroute("/?msg=unknownHotspot");	
		}
	} else {
		$this->reroute("/?msg=unknownHotspot");
	}
});