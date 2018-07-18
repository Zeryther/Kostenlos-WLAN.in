<?php

use KostenlosWLAN\Util;

$app->get("/logout",function(){
	if(Util::isLoggedIn()){
		session_destroy();
	}

	$this->reroute("/");
});