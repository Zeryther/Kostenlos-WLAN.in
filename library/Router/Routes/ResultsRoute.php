<?php

$app->bind("/:city/:zip",function($params){
	$zipCode = ZipCode::getCode($params["zip"]);

	if($zipCode != null){
		if($zipCode->getCity() == $params["city"]){
			$data = [
				"title" => $zipCode->getCity() . " " . $zipCode->getZipCode(),
				"zipCode" => $zipCode,
				"wrapperHeadline" => Util::formatNumber($zipCode->totalResults()) . " Ergebnisse gefunden in " . $zipCode->getCity()
			];
		
			return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Results.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
		} else {
			$this->reroute("/" . $zipCode->getCity() . "/" . $zipCode->getZipCode());
		}
	} else {
		$this->reroute("/?msg=placeNotFound");
	}
});