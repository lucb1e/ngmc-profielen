<?php 
	function myhash($password) {
		for ($i = 0; $i < 2500; $i++)
			$password = sha1($password . "niouibeohh87403qh873hui%%%epajrnkleuroiuhui3h408uhriejndlfjbpuai:D");
		
		return $password;
	}
	
	function checkIngelogd() {
		if (!isIngelogd()) {
			header("HTTP/1.1 302 Moved Temporarily");
			header("Location: ./?page=inloggen");
			exit;
		}
		
		if ($_SESSION["profiel_geactiveerd"] != true) {
			header("HTTP/1.1 302 Moved Temporarily");
			header("Location: ./?page=activatie&profiel=" . intval($_SESSION["profielid"]));
			exit;
		}
	}
	
	function isIngelogd() {
		return (isset($_SESSION["profielid"]) && !empty($_SESSION["profielid"]));
	}
	
	function file_get_ngmc($request) {
		$sock = fsockopen("www.game-maker.nl", 80, $use, $less, 15)
			or die("Socket error");
		
		fwrite($sock, $request);
		
		$i = 0;
		$data = "";
		while ($i++ < 15 && ($line = fread($sock, 1024 * 10)))
			$data .= $line;
		
		return $data;
	}
	
	function activate($profiel, $code) {
		global $cookie;
		
		$profiel = intval($profiel);
		
		$data = file_get_ngmc("GET /forums/action,profile/u," . $profiel . " HTTP/1.1\r\nHost: www.game-maker.nl\r\nCookie: " . $cookie . "\r\n\r\n");
		
		return strpos($data, $code);
	}
	
	function profielIdBijGebruikersnaam($gebruikersnaam) {
		global $cookie;
		
		$postdata = "submit=Zoek&search=" . urlencode($gebruikersnaam) . "&fields%5B%5D=name";
		
		$data = file_get_ngmc("POST /forums/action,mlist/sa,search HTTP/1.1\r\n"
			. "Host: www.game-maker.nl\r\n"
			. "Cookie: " . $cookie . "\r\n"
			. "Content-Type: application/x-www-form-urlencoded\r\n"
			. "Content-length: " . strlen($postdata) . "\r\n"
			. "\r\n"
			. $postdata);
		
		$data = substr($data, strpos($data, "Bekijk profiel van ") - 20, 40);
		$data = substr($data, strpos($data, "/u,") + 3, 7);
		$data = substr($data, 0, strpos($data, '"'));
		
		return $data; // userid
	}

