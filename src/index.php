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
			require("pages/mijnprofiel.php");
			exit;
		
		case "inloggen":
			require("pages/inloggen.php");
			exit;
		
		case "activatie":
			require("pages/activatie.php");
			exit;
		
		case "tagtoevoegen":
			require("pages/tagtoevoegen.php");
			exit;
		
		case "ajax-zoektag":
			require("pages/ajax-zoektag.php");
			exit;
		
		case "":
			require("pages/homepage.php");
			exit;
		
		default:
			die("De opgevraagde pagina kon niet worden gevonden.");
	}