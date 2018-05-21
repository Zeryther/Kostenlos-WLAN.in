<?php

$app->get("/search",function(){
	if(isset($_GET["q"]) && !empty($_GET["q"])){
		$query = $_GET["q"];
		$zipCode = null;

		if(is_numeric($query)){
			// ZIP CODE
			$zipCode = ZipCode::getCode($query);
		} else {
			// CITY NAME
			$zipCode = ZipCode::getCodeFromCity($query);
		}

		if(!is_null($zipCode)){
			$this->reroute("/" . $zipCode->getCity() . "/" . $zipCode->getZipCode());
		} else {
			$this->reroute("/?msg=placeNotFound");
		}
	} else {
		$this->reroute("/");
	}
});