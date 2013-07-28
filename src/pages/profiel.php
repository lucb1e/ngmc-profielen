<?php 
	if (!isset($db))
		exit;
	
	if (empty($_GET["profiel"])) {
		header("HTTP/1.1 302 Moved Temporarily");
		header("Location: ./?page=profielen");
		exit;
	}
	
	$result = $db->query("
		SELECT gebruikersnaam, naam, locatiex, locatiey, geboortedatum, profiel_publiek, bio
		FROM users
		WHERE userid = " . intval($_GET["profiel"]))
		or die("Database error 4289355");
	
	if ($result->num_rows != 1)
		die("Dit profiel bestaat niet (meer)! Ga terug naar <a href='./?page=profielen'>de lijst met profielen</a>.");
	
	$result = $result->fetch_row();
	
	$gebruikersnaam = htmlentities($result[0]);
	$echtenaam = htmlentities($result[1]);
	$locatiex = $result[2];
	$locatiey = $result[3];
	
	if ($result[4] == -1)
		$leeftijd = "";
	else
		$leeftijd = floor((time() - $result[4]) / (3600 * 24 * 365.25));
	
	$profiel_publiek = $result[5] == 1;
	$info = htmlentities($result[6]);
	
	$result = $db->query("
		SELECT t.naam, ut.opmerking
		FROM users_tags ut
		INNER JOIN tags t ON t.id = ut.tagid
		WHERE ut.userid = " . intval($_GET["profiel"]))
		or die("Database error 3128941");
	
	$tags = "";
	while ($row = $result->fetch_row()) {
		$tags .= htmlentities($row[0]) . " - " . htmlentities($row[1]) . "<br/>";
	}
	if ($tags == "")
		$tags = "Geen";
	
	$kaart = "&lt;kaart komt hier>";
	
	include("header.php");
?>
<h3>Profiel van: <?php echo $naam; ?></h3>

<table>
	<tr><td>Gebruikersnaam:</td><td><?php echo $gebruikersnaam; ?></td></tr>
	<tr><td>Echte naam:</td><td><?php echo $echtenaam; ?></td></tr>
	<tr><td>Leeftijd:</td><td><?php echo $leeftijd; ?></td></tr>
	<tr><td>Info:</td><td><?php echo $info; ?></td></tr>
	<tr><td>Tags:</td><td><?php echo $tags; ?></td></tr>
	<tr><td>Locatie:</td><td><?php echo $kaart; ?></td></tr>
</table>

<?php
	include("footer.php");

