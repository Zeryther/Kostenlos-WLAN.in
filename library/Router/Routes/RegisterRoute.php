<?php

$app->get("/register",function(){
	if(!Util::isLoggedIn()){
		$this->reroute("https://gigadrivegroup.com/register");
	} else {
		$this->reroute("/");
	}
});