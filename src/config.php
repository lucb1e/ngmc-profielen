<?php 
	$db = new mysqli("p:localhost", "user", "password", "database");
	if ($db->connect_error)
		die("Database connection error 1.");
	
	// Dit bestand set de cookie in $cookie. Vereist voor bepaalde integratie.
	require("../mycookie.php");
