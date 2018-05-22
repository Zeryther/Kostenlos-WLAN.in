<?php

$app->get("/login", function(){
	if(!Util::isLoggedIn()){
		$redir = "http" . (isset($_SERVER["HTTPS"]) ? "s" : "") . "://" . $_SERVER["HTTP_HOST"] . "/loginCallback";
		$url = "https://gigadrivegroup.com/login?redir=" . urlencode($redir) . "&permissions=email";

		$this->reroute($url);
	} else {
		$this->reroute("/");
	}
});