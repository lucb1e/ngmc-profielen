<?php 
	require("config.php");
	require("functions.php");
	session_start();
	
	// De volgende if-statements die de csrf token checken zijn niet nodig,
	// maar het is een soort extra beveiliging voor als we het in het form
	// vergeten te checken. En bij de 'case "uitloggen"' verder naar beneden
	// kunnen we de check nu weglaten.
	if (isset($_GET["csrf"]))
		if ($_GET["csrf"] != $_SESSION["csrf"])
			exit;
	
	if (isset($_POST["csrf"]))
		if ($_POST["csrf"] != $_SESSION["csrf"])
			exit;
	
	if(!isset($_GET["page"]))
		$_GET["page"] = "";
	
	switch ($_GET["page"]) {
		case "profielen":
			require("pages/profielen.php");
			exit;
		
		case "profiel":
			require("pages/profiel.php");
			exit;
		
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
		
		case "uitloggen":
			session_destroy();
			header("HTTP/1.1 302 Moved Temporarily");
			header("Location: ./");
			exit;
		
		case "":
			require("pages/homepage.php");
			exit;
		
		default:
			die("De opgevraagde pagina kon niet worden gevonden.");
	}

