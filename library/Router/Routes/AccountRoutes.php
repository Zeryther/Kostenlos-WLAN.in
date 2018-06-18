<?php

$app->bind("/account",function(){
	if(Util::isLoggedIn()){ // TODO
		$data = [
			"title" => "Mein Account",
			"printAccountNav" => true,
			"accountNav" => ACCOUNT_NAV_HOME
		];
	
		return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Account.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
	} else {
		return $this->reroute("/login");
	}
});

$app->bind("/account/logout",function(){
	session_start();
	session_destroy();
	return $this->reroute("/?msg=loggedOut");
});