<?php

$app->get("/search",function(){
	if(isset($_GET["q"]) && !empty($_GET["q"])){
		$query = $_GET["q"];
		$zipCode = null;

		if(is_integer($query)){
			// ZIP CODE
			$zipCode = ZipCode::getCode($query);
		} else {
			// CITY NAME
			$zipCode = ZipCode::getCodeFromCity($query);
		}

		if(!is_null($zipCode)){
			return $zipCode;
		} else {
			$this->reroute("/?msg=placeNotFound");
		}
	} else {
		$this->reroute("/");
	}
});