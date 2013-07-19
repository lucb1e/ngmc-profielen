<?php 
	if (!isset($db))
		exit;
	
	checkIngelogd();
	
	if (!empty($_GET["deletetagid"]) && $_SESSION["csrf"] == $_GET["csrf"]) {
		$db->query("DELETE FROM users_tags WHERE id = " . intval($_GET["deletetagid"]))
			or die("Database error 1627442");
	}
	
	if (isset($_POST["bio"]) && $_SESSION["csrf"] == $_POST["csrf"]) {
		if ($_POST["openbaarprofiel"] == 1 || $_POST["openbaarprofiel"] == 0) {
			$db->query("UPDATE users SET bio = '" . $db->escape_string($_POST["bio"]) . "', profiel_publiek = '" . intval($_POST["openbaarprofiel"]) . "' WHERE userid = " . intval($_SESSION["profielid"]))
				or die("Database error 1428592");
			
			$message = "<font color=green >Opgeslagen!</font>";
		}
	}
	
	$result = $db->query("SELECT profiel_publiek, bio FROM users WHERE userid = " . intval($_SESSION["profielid"]))
		or die("Database error 6510924");
	
	$row = $result->fetch_row();
	$selected_openbaar = $row[0] == 1 ? "selected" : "";
	$selected_nietopenbaar = $row[0] == 0 ? "selected" : "";
	$bio = htmlspecialchars($row[1]);
	
	$result = $db->query("
		SELECT ut.opmerking, t.naam, ut.id
		FROM users_tags ut
		INNER JOIN tags t ON t.id = ut.tagid
		WHERE ut.userid = " . intval($_SESSION["profielid"]))
		or die("Database error 8920143");
	
	$tags = [];
	
	while ($row = $result->fetch_row()) {
		$tags[] = array("opmerking" => $row[0], "naam" => $row[1], "id" => $row[2]);
	}
	
	include("header.php");
?>
<h3>Mijn profiel</h3>

<?php 
	if (isset($message))
		echo $message . "<br/><br/>";
?>

<form method="post" action="./?page=mijnprofiel">
	<input type=hidden name=csrf value='<?php echo $_SESSION["csrf"];?>' />
	Je hebt het meeste al in kunnen vullen bij het registreren. Op dit moment is het enkel mogelijk de resterende velden aan te vullen, de mogelijkheid tot de rest bewerken komt later.<br/>
	<br/>
	Profiel zichtbaar voor niet-ingelogde gebruikers (aanbevolen):<br/>
	<select name=openbaarprofiel >
		<option value=1 <?php echo $selected_openbaar; ?>>Ja</option>
		<option value=0  <?php echo $selected_nietopenbaar; ?>>Nee</option>
	</select><br/>
	<br/>
	Iets over jezelf:<br/>
	<textarea name=bio style="width: 100%" rows=10 ><?php echo $bio; ?></textarea><br/>
	<br/>
	<input type=submit value=Opslaan /><br/>
	<br/>
</form>
Jouw tags:
<table>
	<tr><td><b>Tag</b></td><td><b>Opmerking</b></td><td></td></tr>
	<?php
		if (count($tags) == 0) {
			echo "<tr><td>-</td><td>Je hebt nog geen tags toegevoegd.</td><td></td></tr>";
		}
		foreach ($tags as $tag) {
			$opmerking = empty($tag["opmerking"]) ? "-" : htmlspecialchars($tag["opmerking"]);
			echo "<tr>";
			echo "<td>" . htmlspecialchars($tag["naam"]) . "</td>";
			echo "<td>" . $opmerking . "</td>";
			echo "<td><a href='./?page=mijnprofiel&deletetagid=" . intval($tag["id"]) . "&csrf=" . $_SESSION["csrf"] . "'><img src='res/images/delete.png' border=0 /></a></td>";
			echo "</tr>";
		}
	?>
</table>
<a href="./?page=tagtoevoegen">Tag toevoegen</a>

<?php
	include("footer.php");
?>