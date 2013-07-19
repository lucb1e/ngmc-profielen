<?php 
	if (!isset($db))
		exit; // direct object reference
	
	if (isset($_POST["gebruikersnaam"])) {
		$result = $db->query("SELECT profielverificatie,  FROM users WHERE gebruikersnaam = '" . $db->escape_string($_POST["gebruikersnaam"]) . "' AND wachtwoord = '" . myhash($_POST["wachtwoord"]) . "'")
			or die("Database error 571390.");
		
		if ($result->num_rows == 0) {
			$message = "Gebruikersnaam of wachtwoord onjuist.";
		}
		else {
			$row = $result->fetch_row();
			
			if ($row[0] != "") { // De verificatiecode is nog niet leeg; het profiel is nog niet geactiveerd!
				$_SESSION["profiel_geactiveerd"] = false;
				$_SESSION["profielid"] = $row[1];
				
				header("HTTP/1.1 302 Moved Temporarily");
				header("Location: ./?page=activatie&profiel=" . intval($row[1]));
				exit;
			}
			else {
				$_SESSION["profiel_geactiveerd"] = true;
				$_SESSION["profielid"] = $row[1];
				
				header("HTTP/1.1 302 Moved Temporarily");
				header("Location: ./?page=mijnprofiel");
				exit;
			}
		}
	}
	
	include("header.php");
?>
<h3>Inloggen</h3>

<?php
	if (isset($message))
		echo $message . "<br/><br/>";
?>

<form method="post" action="./?page=inloggen">
	<table>
		<tr><td>Gebruikersnaam:</td><td><input name=gebruikersnaam /></td></tr>
		<tr><td>Wachtwoord:</td><td><input type=password name=wachtwoord /></td></tr>
		<tr><td><input type=submit value=Inloggen /></td><td></td></tr>
	</table>
</form>
Een 'wachtwoord vergeten' functie komt binnenkort.