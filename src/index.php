<?php 
	require("config.php");
	require("functions.php");
	session_start();
	
	switch ($_GET["page"]) {
		case "profielen":
			die("Dit is nog in aanbouw.");
		
		case "nieuwprofiel":
			require("pages/nieuwprofiel.php");
			exit;
		
		case "mijnprofiel":
			die("Dit is nog in aanbouw.");
		
		case "inloggen":
			require("pages/inloggen.php");
			exit;
		
		case "activatie":
			require("pages/activatie.php");
			exit;
		
		case "":
			require("pages/homepage.php");
			exit;
		
		default:
			die("De opgevraagde pagina kon niet worden gevonden.");
	}