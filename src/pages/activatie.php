<?php 
	if (!isset($db))
		exit;
	
	if ($_SESSION["profiel_geactiveerd"] == true)
		die("Jouw profiel is al geactiveerd!");
	
	$result = $db->query("SELECT profielverificatie FROM users WHERE userid = " . intval($_GET["profiel"]))
		or die("Database error 7011023");
	
	if ($result->num_rows != 1)
		die("Profiel niet gevonden in de database!");
	
	$row = $result->fetch_row();
	
	if ($row[0] == "")
		die("Jouw profiel is al geactiveerd!");
	
	if ($_GET["check"]) {
		if (activate(intval($_GET["profiel"]), $row[0])) {
			$db->query("UPDATE users SET profielverificatie = '' WHERE userid = " . intval($_GET["profiel"]))
				or die("Database error 735810");
			
			$_SESSION["profiel_geactiveerd"] = true;
			
			header("HTTP/1.1 302 Moved Temporarily");
			header("Location: ./?page=mijnprofiel&activatie-succesvol");
			exit;
		}
		else {
			$message = "<font color=red >Activatiecode niet gevonden in je onderschrift!</font>";
		}
	}
	
	include("header.php");
?>
<h3>Validatie</h3>

<?php 
	if (isset($message))
		echo $message . "<br/><br/>";
?>

Jij zegt dat profiel id <?php echo intval($_GET["profiel"]);?> van jou is, maar klopt dat wel? Helaas moeten we dit
controleren, maar het is best simpel:<br/>
<br/>
Plaats de volgende code in je onderschrift:
<pre><?php echo $row[0];?></pre>
<br/>
Dat is alles! Je hoeft je bestaande onderschrift niet weg te halen, alleen de code moet erbij. Na het verifi&euml;ren kun je de code direct weer weghalen.<br/>
<br/>
<a href="http://www.game-maker.nl/forums/action,profile/u,<?php echo intval($_GET["profiel"]);?>/sa,forumProfile" target="_blank">Klik hier om jouw www.game-maker.nl profiel aan te passen.</a><br/>
<br/>
Staat de code erin?<br/>
<br/>
<a href="./?page=activatie&profiel=<?php echo intval($_GET["profiel"]);?>&check=1">Controleren!</a>