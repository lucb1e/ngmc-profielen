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
			
			$message = "De wijzigingen zijn opgeslagen!";
		}
	}
	
	$result = $db->query("SELECT profiel_publiek, bio FROM users WHERE userid = " . intval($_SESSION["profielid"]))
		or die("Database error 6510924");
	
	$row = $result->fetch_row();
	$selected_openbaar = $row[0] == 1 ? "selected=\"selected\"" : "";
	$selected_nietopenbaar = $row[0] == 0 ? "selected=\"selected\"" : "";
	$bio = htmlspecialchars($row[1]);
	
	$result = $db->query("
		SELECT ut.opmerking, t.naam, ut.id
		FROM users_tags ut
		INNER JOIN tags t ON t.id = ut.tagid
		WHERE ut.userid = " . intval($_SESSION["profielid"]))
		or die("Database error 8920143");
	
	$tags = array();
	
	while ($row = $result->fetch_row()) {
		$tags[] = array("opmerking" => $row[0], "naam" => $row[1], "id" => $row[2]);
	}
	
	include("header.php");
?>
<h3>Mijn profiel</h3>
<?php 
	if (isset($message))
		echo "<div class=\"message green\">" . $message . "</div>";
?>
<p>Je hebt het meeste al in kunnen vullen bij het registreren. Op dit moment is het enkel mogelijk de resterende velden aan te vullen, de mogelijkheid tot de rest bewerken komt later.</p>
<form method="post" action="./?page=mijnprofiel"><tr>
	<input type="hidden" name="csrf" value="<?=$_SESSION["csrf"] ?>" />
<table class="noborder">
<tr><td width="40%">Profiel zichtbaar voor niet-ingelogde gebruikers (aanbevolen):</td>
	<td><select name="openbaarprofiel" class="override-width override-background">
		<option value="1" <?=$selected_openbaar ?>>Ja</option>
		<option value="0" <?=$selected_nietopenbaar ?>>Nee</option>
	</select></td></tr>
<tr><td>Iets over jezelf:</td>
	<td><textarea name="bio" class="max" rows="10"><?php echo $bio; ?></textarea></td></tr>
<tr><td colspan="2"><input type="submit" value="Opslaan" class="float-right override-width"></td></tr></table>
</form>
<h3>Mijn tags</h3>
<p>Hieronder staan jouw tags. Tags beschrijven korte, maar kenmerkende eigenschappen die jou maken wie je bent. Tags kunnen bijvoorbeeld PHP, CSS en HTML, maar ook Creatief, Slim en Sociaal zijn.</p>
<table>
	<thead><td>Tag</td><td>Opmerking</td><td></td></thead>
	<?php
		if (count($tags) == 0) {
			echo "<tr><td>-</td><td>Je hebt nog geen tags toegevoegd.</td><td></td></tr>";
		}
		foreach ($tags as $tag) {
			$opmerking = empty($tag["opmerking"]) ? "-" : htmlspecialchars($tag["opmerking"]);
			echo "<tr>";
			echo "<td>" . htmlspecialchars($tag["naam"]) . "</td>";
			echo "<td>" . $opmerking . "</td>";
			echo "<td><a href=\"./?page=mijnprofiel&deletetagid=" . intval($tag["id"]) . "&csrf=" . $_SESSION["csrf"] . "\"><img src=\"res/images/delete.png\" border=\"0\" /></a></td>";
			echo "</tr>";
		}
	?>
</table>
<a href="./?page=tagtoevoegen" class="button float-right">Tag toevoegen</a>
<div class="clear"></div>
<?php
	include("footer.php");
?>