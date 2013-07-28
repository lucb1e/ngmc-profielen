<?php 
	$db = new mysqli("p:localhost", "user", "password", "database");
	if ($db->connect_error)
		die("Database connection error 1.");
	
	/* Deze cookie is nodig om:
	   - Gebruikers id's op te zoeken a.d.h.v. usernames (bij registreren)
	   - Gebruikersprofielen te kunnen bekijken (bij profielverificatie)
	   Je kan de cookie vinden in de javascript variabele 'document.cookie'
	   wanneer je ingelogd bent op het forum. Zorg wel dat je niet uitlogt
	   met die cookie, dan werkt (jouw kopie van) de site ook niet meer!
	   Ofja, het registreren en verifieren werkt niet meer, de rest wel.
	*/
	$cookie = "your cookie";
	
	// 173px op kaart zijn 201km in het echt
	$kaart_px_to_km = (201 / 173);
