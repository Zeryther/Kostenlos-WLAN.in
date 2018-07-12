<?php

$app->get("/loginCallback", function(){
	if(!Util::isLoggedIn()){
		if(isset($_GET["code"])){
			$url = "https://api.gigadrivegroup.com/v3/gettoken?secret=" . GIGADRIVE_API_KEY . "&code=" . urlencode($_GET["code"]);
			$result = file_get_contents($url);
			$j = json_decode($result,true);

			if(isset($j["success"]) && !empty($j["success"]) && isset($j["token"]) && !empty($j["token"])){
				$token = $j["token"];

				$url = "https://api.gigadrivegroup.com/v3/userdata?secret=" . GIGADRIVE_API_KEY . "&token=" . urlencode($token);
				$result = file_get_contents($url);
				$j = json_decode($result,true);

				if(isset($j["success"]) && !empty($j["success"]) && isset($j["user"])){
					$userData = $j["user"];

					if(isset($userData["email"]) && isset($userData["id"]) && isset($userData["username"])){
						$userID = $userData["id"];
						$userName = $userData["username"];
						$userEmail = $userData["email"];
	
						$_SESSION["id"] = $userID;
						$_SESSION["username"] = $userName;
	
						User::registerAccount($userID,$userName,$userEmail,$token);
	
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

		/*if(isset($_GET["gigadriveLoginKey"]) && isset($_GET["gigadriveUserID"]) && !empty($_GET["gigadriveLoginKey"]) && !empty($_GET["gigadriveUserID"])){
			$userID = $_GET["gigadriveUserID"];
			$loginKey = $_GET["gigadriveLoginKey"];

			$url = "https://api.gigadrivegroup.com/v2/login/?apiKey=" . GIGADRIVE_API_KEY . "&userID=" . $userID . "&loginKey=" . $loginKey;
			$result = file_get_contents($url);
			$j = json_decode($result,true);

			if(isset($j["success"]) && !empty($j["success"]) && $j["success"] == "Authentication successful"){
				if(isset($j["data"])){
					$data = $j["data"];

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
		}*/
	} else {
		$this->reroute("/");
	}
});