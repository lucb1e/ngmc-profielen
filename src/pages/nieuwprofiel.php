<?php 
	if (!isset($db))
		exit;
	
	if(isIngelogd()) {
		header("HTTP/1.1 302 Moved Temporarily");
		header("Location: ./?page=mijnprofiel");
		exit;
	}
	
	if (isset($_POST["gebruikersnaam"])) {
		$ok = true;
		
		/*$userid = intval(
			trim(
			str_replace("/sa,statPanel", "",
			str_replace("http://www.game-maker.nl/forums/action,profile/", "",
			str_replace("u,", "",
			$_POST["userid"]
			)))));*/
		
		$userid = profielIdBijGebruikersnaam($_POST["gebruikersnaam"]);
		
		$geboortejaar = intval($_POST["geboortejaar"]);
		$geboortedag = intval($_POST["geboortedag"]);
		$geboortemaand = intval($_POST["geboortemaand"]);
		
		if ($geboortejaar != $_POST["geboortejaar"] || $geboortejaar < 1 || $geboortejaar >= date("Y")) {
			$geboortejaar = -1; // geboortejaar is standaard "19.." en is optioneel.
		}
		
		if ($ok && ($_POST["geboortedag"] != $geboortedag || $geboortedag > 31 || $geboortedag < 1)) {
			$message = "Ongeldige geboortedag.";
			$ok = false;
		}
		
		if ($ok && ($_POST["geboortemaand"] != $geboortemaand || $geboortemaand < 0 || $geboortemaand > 11)) {
			$message = "Hackzor. Knap hoor :)"; // Het is een dropdown menu, iemand moet zich al moeite doen om dit voor elkaar te krijgen.
			$ok = false;
		}
		
		if ($ok && (strlen($_POST["wachtwoord"]) < 5 || strlen($_POST["wachtwoord"]) > 9000)) {
			$message = "Wachtwoord ongeldig! Je wachtwoord mag niet korter zijn dan 5 tekens (of langer dan 9000).";
			$ok = false;
		}
		
		/*if ($ok && $userid != $_POST["userid"] || $userid > 1000 * 1000 || $userid < 1) {
			$ok = false;
			$message = "Ongeldig gebruikersid.";
		}*/
		
		if ($ok) {
			$result = $db->query("SELECT userid
				FROM users
				WHERE (gebruikersnaam = '" . $db->escape_string($_POST["gebruikersnaam"]) . "'
					OR userid = " . intval($_POST["userid"]) . ")
					AND profielverificatie = ''")
				or die("Database error 1976384");
			
			if ($result->num_rows !== 0) {
				$message = "Deze gebruikersnaam is al geregistreerd!";
				$ok = false;
			}
		}
		
		if ($ok) {
			$wachtwoord = myhash($_POST["wachtwoord"]);
			if (strpos($_POST["locatie"], ",") === false) {
				$locatiex = -1;
				$locatiey = -1;
			}
			else {
				$locatie = explode(",", $_POST["locatie"]);
				if ($locatie[0] != intval($locatie[0]) || $locatie[1] != intval($locatie[1])) {
					$locatiex = -1;
					$locatiey = -1;
				}
				else {
					$locatiex = $locatie[0];
					$locatiey = $locatie[1];
				}
			}
			
			if ($geboortejaar == -1)
				$geboorteint = -1;
			else
				// geboortemaand is 0..11, maar PHP wil 1 als januari en 12 als december
				$geboorteint = strtotime($geboortejaar . "-" . ($geboortemaand + 1) . "-" . $geboortedag);
			
			$result = $db->query("SELECT userid FROM users WHERE userid = " . $userid)
				or die("Database error 573818");
			
			if ($result->num_rows > 0) {
				$db->query("
					UPDATE
						users
					SET 
						wachtwoord = '" . $wachtwoord . "',
						naam = '" . $db->escape_string($_POST["naam"]) . "',
						locatiex = " . $locatiex . ",
						locatiey = " . $locatiey . ",
						geboortedatum = " . $geboorteint . ",
						profielverificatie = '" . substr(myhash($userid . "|" . rand()), 0, 7) . "'
					WHERE
						userid = " . $userid)
					or die("Database error 1478293");
			}
			else {
				$db->query("
					INSERT INTO users
						(userid, gebruikersnaam, wachtwoord, naam, locatiex, locatiey, geboortedatum, profielverificatie)
					VALUES (" . $userid . "
						, '" . $db->escape_string($_POST["gebruikersnaam"]) . "'
						, '" . $wachtwoord . "'
						, '" . $db->escape_string($_POST["naam"]) . "'
						, " . $locatiex . "
						, " . $locatiey . "
						, " . $geboorteint . "
						, '" . substr(myhash($userid . "|" . rand()), 0, 7) . "')")
					or die("Database error 1903293");
			}
			
			$_SESSION["profiel_geactiveerd"] = false;
			$_SESSION["profielid"] = $userid;
			$_SESSION["csrf"] = myhash(rand() . myhash(myhash($_POST["wachtwoord"]) . rand()));
			
			header("HTTP/1.1 302 Moved Temporarily");
			header("Location: ./?page=activatie&profiel=" . $userid);
			exit;
		}
	}
	
	include("header.php");
?>
<h3>Registreren</h3>
<?php
	if (!empty($message))
		echo '<div class="message red">' . $message . '</div>';
?>
<form method="post" action="./?page=nieuwprofiel">
	<table class="noborder">
		<tr><td width="50%"><b>Gebruikersnaam:</b></td><td><input name=gebruikersnaam class=max /></td></tr>
		<tr><td colspan=2>Let op: Je gebruikersnaam moet hetzelfde zijn als op www.game-maker.nl!</td></tr>
		<tr><td><b>Echte naam:</b></td><td><input name=naam class=max /> (optioneel)</td></td></tr>
		<!--<tr><td><b>Gebruikersid:</b></td><td><input name=userid size=5 /></td></tr>
		<tr>
			<td colspan=2 >Je gebruikersid kun je vinden door naar <a href='http://www.game-maker.nl/forums/action,profile' target='_blank'>deze pagina te gaan</a>
			en dan links op statistieken te klikken. In de adresbalk staat iets als "u,12345", waar 12345 jouw gebruikersid is.<br/>
			<br/></td>
		</tr>-->
		<tr><td><b>Wachtwoord:</b></td><td><input type=password name=wachtwoord class=max /></td></tr>
		<tr><td valign=top ><b>Geboortedatum</b></td>
			<td><input name=geboortedag size=2 value=1 class=override-width />
				<select name=geboortemaand class="override-width override-background" >
					<option value=0 >Januari</option>
					<option value=1 >Februari</option>
					<option value=2 >Maart</option>
					<option value=3 >April</option>
					<option value=4 >Mei</option>
					<option value=5 >Juni</option>
					<option value=6 >Juli</option>
					<option value=7 >Augustus</option>
					<option value=8 >September</option>
					<option value=9 >Oktober</option>
					<option value=10 >November</option>
					<option value=11 >December</option>
				</select>
				<input name=geboortejaar size=4 value="19.." onfocus="value.indexOf('.')==2 ? value = '' : '';" class=override-width /><br/>
				Optioneel, maar vul wel je geboortejaar in, dan hebben we enig idee of je 10 of 28 bent!
			</td>
		</tr>
		<tr>
			<td valign=top >
				<b>Locatie</b><br/>
				<br/>
				Klik op de kaart!<br/>
				De co&ouml;rdinaten waar je geklikt hebt worden opgeslagen. <noscript><font color=red >Oh je hebt NoScript: Wat hiervoor stond was een leugen. Je hebt Javascript uitgeschakeld dus dat werkt niet.</font></noscript>
			</td>
			<td>
				<img src="res/images/benelux.png" id=kaart />
				<input type=hidden name=locatie id=locatie />
				<div id="locatie-output"><a href='javascript: nietInNederland();'>Niet in Nederland/Belgi&euml;</a></div>
			</td>
		</tr>
		<tr><td><input type=submit value=Registreren /></td><td></td></tr>
	</table>
	
</form>

<?php 
	include("footer.php");
?>

<script src="res/js/kaartlocatie.js"></script>
<script>
	var image_kaart = document.getElementById("kaart");
	var input_locatie = document.getElementById("locatie");
	var locatie_div = document.getElementById("locatie-output");
	
	image_kaart.onclick = function(ev) {
		var coords = GetCoordinates(ev);
		input_locatie.value = coords[0] + "," + coords[1];
		
		locatie_div.innerHTML = "Opgeslagen! (" + input_locatie.value + ")";
		
		if (coords[0] < 80 && coords[1] < 120)
			confirm("Op dat boorplatform in de noordzee bedoel je? :D");
	};
	
	function nietInNederland() {
		alert("Andere landen is op dit moment nog niet mogelijk. Als je hierover gaat klagen komt de mogelijkheid er zeker in! :D");
	}
</script>
