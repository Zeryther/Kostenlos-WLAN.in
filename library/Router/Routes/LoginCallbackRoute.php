<?php

$app->get("/loginCallback", function(){
	if(!Util::isLoggedIn()){
		if(isset($_GET["gigadriveLoginKey"]) && isset($_GET["gigadriveUserID"]) && !empty($_GET["gigadriveLoginKey"]) && !empty($_GET["gigadriveUserID"])){
			$userID = $_GET["gigadriveUserID"];
			$loginKey = $_GET["gigadriveLoginKey"];

			$url = "https://api.gigadrivegroup.com/v2/login/?apiKey=" . GIGADRIVE_API_KEY . "&userID=" . $userID . "&loginKey=" . $loginKey;
			$result = file_get_contents($url);
			$j = json_decode($result,true);

			if(isset($j["success"]) && !empty($j["success"]) && $j["success"] == "Authentication successful"){
				if(isset($j["data"])){
					$data = $j["data"];
	
					if(isset($data["externalAccounts"]) && isset($data["externalAccounts"]["MINECRAFT"])){
						$uuid = $data["externalAccounts"]["MINECRAFT"];
					}

					$userID = $data["id"];
					$userName = $data["username"];
					$userEmail = $data["email"];

					$_SESSION["id"] = $userID;
					$_SESSION["username"] = $userName;

					User::registerAccount($userID,$userName,$userEmail);

					$this->reroute("/?msg=loggedIn");
				} else {
					$this->reroute("/");
				}
			} else {
				$this->reroute("/");
			}
		} else {
			$this->reroute("/");
		}
	} else {
		$this->reroute("/");
	}
});