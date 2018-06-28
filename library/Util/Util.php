<?php

define("USERRANK_USER","USER");
define("USERRANK_ADMIN","ADMIN");

define("AD_TYPE_LEADERBOARD","adLeaderboard");
define("AD_TYPE_HORIZONTAL","adLeaderboard");
define("AD_TYPE_BLOCK","adBlock");
define("AD_TYPE_VERTICAL","adVertical");

define("ALERT_TYPE_INFO","info");
define("ALERT_TYPE_WARNING","warning");
define("ALERT_TYPE_DANGER","danger");
define("ALERT_TYPE_SUCCESS","success");
define("ALERT_TYPE_SECONDARY","secondary");
define("ALERT_TYPE_LIGHT","light");
define("ALERT_TYPE_PRIMARY","primary");

define("ACCOUNT_NAV_HOME","ACCOUNT_NAV_HOME");
define("ACCOUNT_NAV_RATINGS","ACCOUNT_NAV_RATINGS");
define("ACCOUNT_NAV_HOTSPOTS","ACCOUNT_NAV_HOTSPOTS");
define("ACCOUNT_NAV_LOGOUT","ACCOUNT_NAV_LOGOUT");

define("ADMIN_NAV_PENDING_SPOTS","ADMIN_NAV_PENDING_SPOTS");
define("ADMIN_NAV_OPEN_REPORTS","ADMIN_NAV_OPEN_REPORTS");

define("FILTER_MAX_DISTANCE_MINIMUM",1);
define("FILTER_MAX_DISTANCE_MAXIMUM",150);

define("REPORT_REASON_BROKEN_HOTSPOT","BROKEN_HOTSPOT");
define("REPORT_REASON_INVALID_DATA","INVALID_DATA");
define("REPORT_REASON_SPAM","SPAM");

define("REPORT_STATUS_OPEN","OPEN");
define("REPORT_STATUS_ACCEPTED","ACCEPTED");
define("REPORT_STATUS_DENIED","DENIED");

define("LEFT_QUOTES","&#x84;");
define("RIGHT_QUOTES","&#148;");

class Util {
	public static function isLoggedIn(){
		return isset($_SESSION["id"]);
	}

	public static function getCurrentUser(){
		return self::isLoggedIn() == true ? User::getUserById($_SESSION["id"]) : null;
	}

	public static function timeago($timestamp){
		$timestamp = date("Y",strtotime($timestamp)) . "-" . date("M",strtotime($timestamp)) . "-" . date("d",strtotime($timestamp)) . "T" . date("H",strtotime($timestamp)) . ":" . date("i",strtotime($timestamp)) . ":" . date("s",strtotime($timestamp)) . "Z";

		return '<time class="timeago" datetime="' . $timestamp . '">' . $timestamp . '</time>';
	}

	public static function createAlert($id,$text,$type = ALERT_TYPE_INFO,$dismissible = FALSE,$saveDismiss = FALSE){
		$cookieName = "registeredAlert" . $id;

		if($dismissible == false){
			echo '<div id="registeredalert' . $id . '" class="alert alert-' . $type . '">' . $text . '</div>';
		} else {
			if($saveDismiss == false || ($saveDismiss == true && !isset($_COOKIE[$cookieName]))){
				$d = $saveDismiss == true ? ' onClick="saveDismiss(\'' . $id . '\');"' : "";
				echo '<div id="registeredalert' . $id . '" class="text-left alert alert-dismissible alert-' . $type . '"><button id="registeredalertclose' . $id . '" type="button" class="close" data-dismiss="alert"' . $d . '>&times;</button>' . $text . '</div>';
			}
		}
	}

	public static function quote($s){
		return LEFT_QUOTES . $s . RIGHT_QUOTES;
	}

	public static function getRandomString($length = 16) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < rand(4,$length); $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public static function getIP(){
		$ip = "undefined";
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}

	public static function cleanupTempFolder(){
		$files = glob($_SERVER["DOCUMENT_ROOT"] . "/tmp/*");
		$now = time();

		foreach($files as $file){
			if(is_file($file) && basename($file) != ".keep"){
				if($now - filemtime($file) >= 60*60*24){
					unlink($file);
				}
			}
		}
	}

	public static function formatNumber($num){
		return number_format($num, 0, '', '.');
	}

	public static function geoIPData($ip){
		if($ip == null || empty($ip)) $ip = self::getIP();
		$n = "geoIPData_" . urlencode($ip);
		
		if(CacheHandler::existsInCache($n)){
			return json_decode(CacheHandler::getFromCache($n),true);
		} else {
			$data = json_decode(@file_get_contents("http://api.ipstack.com/" . $ip . "?access_key=" . IPSTACK_KEY . "&format=1"),true);

			if(isset($data["city"])){
				CacheHandler::setToCache($n,json_encode($data),20*60);
			}

			return $data;
		}
	}

	public static function fixUmlaut($input){
		$a = [
			"ae" => "ä",
			"oe" => "ö",
			"ue" => "ü"
		];

		foreach($a as $b => $c){
			$input = str_replace($b,$c,$input);
		}

		return $input;
	}

	public static function renderAd($type){
		if($type == AD_TYPE_LEADERBOARD){
			echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<ins class="adsbygoogle"
				style="display:block"
				data-ad-client="ca-pub-6156128043207415"
				data-ad-slot="1055807482"
				data-ad-format="auto"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>';
		} else if($type == AD_TYPE_VERTICAL){
			echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<ins class="adsbygoogle"
				 style="display:inline-block;width:120px;height:600px"
				 data-ad-client="ca-pub-6156128043207415"
				 data-ad-slot="1788401303"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>';
		} else if($type == AD_TYPE_BLOCK){
			echo '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<ins class="adsbygoogle"
				style="display:inline-block;width:336px;height:280px"
				data-ad-client="ca-pub-6156128043207415"
				data-ad-slot="7069637483"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
		</script>';
		}
	}

	public static function limitString($string,$length,$addDots = false){
		if(strlen($string) > $length)
			$string = substr($string,0,($addDots ? $length-3 : $length)) . ($addDots ? "..." : "");

		return $string;
	}
}