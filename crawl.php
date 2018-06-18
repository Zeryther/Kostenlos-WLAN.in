<?php

if((isset($_SERVER["DOCUMENT_ROOT"]) && !empty($_SERVER["DOCUMENT_ROOT"])) || (isset($_SERVER["HTTP_HOST"]) && !empty($_SERVER["HTTP_HOST"]))){
	echo "This script may only be run from the command line!";
	exit();
}

use \ForceUTF8\Encoding;

$_SERVER["DOCUMENT_ROOT"] = __DIR__;
$_SERVER["HTTP_HOST"] = "localhost:3000";
require_once $_SERVER["DOCUMENT_ROOT"] . "/library/Load/Load.php";

$id = 1;

$lastID = -1;
$try = 1;

$opts = array('http' => array('header' => 'Accept-Charset: UTF-8, *;q=0'));
$context = stream_context_create($opts);

while($id <= 894){
	echo "CRAWLING #" . $id . "\n";
	$url = "http://freie-hotspots.de/spotdetail.php?sid=" . $id;
	$content = Encoding::toUTF8(file_get_contents($url,false,$context));
	//$content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));

	$start = "var address_0 = {";
	$end = "infowindow";

	$replace = [
		"street",
		"city",
		"zip",
		"country",
		"infowindow",
		"infowindowtext",
		"full",
		"isdefault",
		"addressType",
		"loop",
		"latitude",
		"longitude",
		"markerStyle",
		"markerColor",
		"state",
		"text",
		"font",

	];

	$b = trim(explode($end,substr($content,strpos($content,$start)+strlen($start),-1))[0]);
	$s = "{" . substr($b,0,strlen($b)-1) . "}";
	$s = str_replace("'","\"",$s);
	foreach($replace as $c){
		$s = str_replace($c,"\"" . $c . "\"",$s);
	}

	$data = json_decode($s,true);

	$start = '<span style="font: 11px Verdana, Arial, Helvetica, sans-serif; color: black;"><strong>';
	$end = '</strong>';
	$name = trim(explode($end,substr($content,strpos($content,$start)+strlen($start),-1))[0]);

	/*var_dump($name);
	exit();*/

	$street = $data["street"];
	$city = $data["city"];
	$zip = $data["zip"];
	$state = $data["state"];

	$latitude = null;
	$longitude = null;

	$googleData = Place::getGoogleGeocodeData($street . " " . $city);
	if($googleData != null && $googleData["status"] == "OK"){
		if(isset($googleData["results"]) && is_array($googleData["results"]) && count($googleData["results"]) > 0){
			foreach($googleData["results"] as $result){
				$result = $googleData["results"][0];
				if(isset($result["formatted_address"]) && strpos($result["formatted_address"],$zip . ", " . $city) !== -1){
					if(isset($result["geometry"])){
						if(isset($result["geometry"]["location"])){
							$street = explode(",",$result["formatted_address"])[0];
							$zip = explode(" ",trim(explode(",",$result["formatted_address"])[1]))[0];
							$city = explode(" ",trim(explode(",",$result["formatted_address"])[1]))[1];
							$latitude = $result["geometry"]["location"]["lat"];
							$longitude = $result["geometry"]["location"]["lng"];

							break;
						}
					}
				}
			}
		}
	}

	if($latitude == null || $longitude == null){
		if($try == 3){
			$try = 1;
			$id++;
		} else {
			sleep(5);

			$try++;
		}
	} else {
		$mysqli = Database::Instance()->get();

		$stmt = $mysqli->prepare("INSERT IGNORE INTO `hotspots` (`name`,`address`,`zipCode`,`city`,`latitude`,`longitude`,`creator`,`valid`) VALUES(?,?,?,?,?,?,3,1);");
		$stmt->bind_param("ssisdd",$name,$street,$zip,$city,$latitude,$longitude);
		if(!$stmt->execute()) echo $stmt->error . "\n";
		$stmt->close();

		$try = 1;
		$id++;
	}
}