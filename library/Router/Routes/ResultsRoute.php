<?php

$app->bind("/:city",function($params){
	$zipCodes = ZipCode::getCodesFromCity($params["city"]);

	if($zipCodes != null && count($zipCodes) > 0){
		$data = [
			"title" => "Kostenlose Hotspots in " . $params["city"],
			"zipCodes" => $zipCodes,
			"city" => $params["city"],
			"wrapperHeadline" => Util::formatNumber(ZipCode::getTotalResultsFromCity($params["city"])) . " Ergebnisse gefunden in " . $params["city"]
		];
	
		return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Results.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
	} else {
		$this->reroute("/?msg=placeNotFound");
	}
});