<?php

$app->get("/search",function(){
	if(isset($_GET["q"]) && !empty($_GET["q"])){
		$query = $_GET["q"];
		$zipCodes = null;
		$city = trim($query);

		if(is_numeric($query)){
			// ZIP CODE
			$zipCode = ZipCode::getCode($query);
			if($zipCode != null){
				$city = $zipCode->getCity();
				$zipCodes = ZipCode::getCodesFromCity($query);
			}
		} else {
			// CITY NAME
			$zipCodes = ZipCode::getCodesFromCity($query);
		}

		if(is_array($zipCodes) && count($zipCodes) > 0){
			$city = ZipCode::getCode($zipCodes[0])->getCity();
			$this->reroute("/" . $city);
		} else {
			$this->reroute("/?msg=placeNotFound");
		}
	} else {
		$this->reroute("/");
	}
});