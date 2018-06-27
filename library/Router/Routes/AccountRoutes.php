<?php

$app->bind("/account",function(){
	if(Util::isLoggedIn()){
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

$app->bind("/account/ratings",function(){
	return $this->reroute("/account/ratings/1");
});

$app->bind("/account/ratings/:page",function($params){
	$page = (isset($params["page"]) && !empty($params["page"]) && is_numeric($params["page"]) && (int)$params["page"] > 0) ? (int)$params["page"] : 1;

	if(Util::isLoggedIn()){
		$itemsPerPage = 10;
        $mysqli = Database::Instance()->get();

		$num = 0;
		$userID = Util::getCurrentUser()->getId();

		$s = $mysqli->prepare("SELECT COUNT(`hotspot`) AS `count` FROM `ratings` WHERE `user` = ?");
		$s->bind_param("i",$userID);
		if($s->execute()){
			$result = $s->get_result();

            $num = $result->num_rows ? $result->fetch_assoc()["count"] : 0;
		}
        $s->close();
        
        $results = [];

        if($num > 0){
			$s = $mysqli->prepare("SELECT * FROM `ratings` WHERE `user` = ? LIMIT " . (($page-1)*$itemsPerPage) . " , " . $itemsPerPage);
			$s->bind_param("i",$userID);
            if($s->execute()){
                $result = $s->get_result();

                while($row = $result->fetch_assoc()){
                    $r = Rating::getRatingFromData($row["hotspot"],$userID,$row["stars"],$row["comment"],$row["time"]);

                    array_push($results,$r);
                }
            }
            $s->close();
        }

		$data = [
			"title" => "Meine Bewertungen - Seite " . $num,
			"printAccountNav" => true,
            "accountNav" => ACCOUNT_NAV_RATINGS,
            "num" => $num,
			"results" => $results,
			"page" => $page,
            "itemsPerPage" => $itemsPerPage
		];
	
		return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/MyRatings.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php",$data);
	} else {
		return $this->reroute("/login");
	}
});

$app->bind("/account/logout",function(){
	session_start();
	
	unset($_SESSION["id"]);
	unset($_SESSION["username"]);
	
	return $this->reroute("/?msg=loggedOut");
});