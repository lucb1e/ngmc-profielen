<?php 
	if (!isset($db))
		exit;
	
	$tags = $db->query("SELECT t.naam, t.id, COUNT(ut.id) FROM tags t INNER JOIN users_tags ut ON ut.tagid = t.id GROUP BY ut.tagid HAVING COUNT(ut.id) > 0 ORDER BY t.naam")
		or die("Database error 75825324");
	
	$users = $db->query("SELECT userid, gebruikersnaam, naam, geboortedatum, bio FROM users WHERE profielverificatie = '' ORDER BY RAND() LIMIT 25")
		or die("Database error 5361932");
	
	require("header.php");
	
	// Dit bestand gebruikt inderdaad een table voor lay-out. Het is de simpelste
	// oplossing die overal werkt. Als je wat beters weet, voel je vrij het aan te passen.
?>
<style>
	hr {
		color: #eaeaea;
	}
	.user {
		margin-top: 5px;
		margin-bottom: 5px;
		background: #fff;
	}
	.user:hover {
		background: #ddd;
		cursor: pointer;
	}
	#kaart {
		cursor: crosshair;
	}
</style>
<h3>Profielen</h3>
<table>
	<tr>
		<td width=150 bgcolor="#dadada" >
			<b><i>Filter</i></b><br/>
			<br/>
			<b>Naam</b><br/>
			<input id=naam /><br/>
			<br/>
			<b>Leeftijd</b><br/>
			Van <input value=0 size=2 /> tot <input size=2 value=99 /><br/>
			<br/>
			<b>Tags</b><br/>
			<?php 
				while ($tag = $tags->fetch_row()) {
					echo "<a href='javascript: filterTag(" . $tag[1] . ");'>" . $tag[0] . " (" . $tag[2] . ")</a><br/>";
				}
			?>
			<br/>
			<b>Locatie</b><br/>
			Binnen <input value=40 size=2 id=straal />km<br/>
			<img src="res/images/benelux.png" style="width: 100%" id="kaart" />
			<div id="locatie-output"></div>
		</td>
		<td valign=top width=500 style="padding-left: 25px;" >
			<?php 
				while ($row = $users->fetch_array()) {
					// Voor nu weet ik geen betere oplossing, maar het fixt de [Todo] tenminste.
					$user_tags = $db->query("SELECT t.naam, t.id, COUNT(ut.id) FROM tags t INNER JOIN users_tags ut ON ut.tagid = t.id AND ut.userid = " . $row["userid"] . " GROUP BY ut.tagid HAVING COUNT(ut.id) > 0 ORDER BY t.naam")
						or die("Database error 69421337");
					echo "<hr>";
					
					$naam = htmlspecialchars(empty($row["naam"]) ? $row["gebruikersnaam"] : $row["naam"]);
					if ($row["geboortedatum"] == -1)
						$leeftijd = "";
					else
						$leeftijd = ", " . floor((time() - $row["geboortedatum"]) / (3600 * 24 * 365.25));
					
					echo "<div class='user' onclick='showDiv(\"user" . $row["userid"] . "\", this);'>" .
						$naam . $leeftijd . ". Tags: ";
					$first = true;
					while ($tag = $user_tags->fetch_row()) {
						echo ($first ? "" : ", ") . $tag[0];
						$first = false;
					}
					echo "<div id='user" . $row["userid"] . "' style='display:none;'>" . 
							"<span style='font-size: 0.8em;'>Info: " . nl2br(htmlentities($row["bio"])) . "</span>" .
						"</div>" .
					"</div>\n";
				}
			?>			
		</td>
	</tr>
</table>
<script src="res/js/kaartlocatie.js"></script>
<script>
	var filters = {
		naam: "", // geen indien niet van toepassing
		leeftijd: [0, 99], // [van, tot]
		tags: [], // leeg indien niet van toepassing
		locatie: [], // [x,y]; geen indien niet van toepassing
		locatie_straal: 40,
		offset: 0 // =($resultaten_per_pagina * $huidige_pagina)
	};
	
	var image_kaart = document.getElementById("kaart");
	var locatie_div = document.getElementById("locatie-output");
	var straal_input = document.getElementById("straal");
	var naam_input = document.getElementById("naam");
	
	naam_input.onkeyup = naam_input.onchange = function() {
		// TODO: this
	};
	
	image_kaart.onclick = function(ev) {
		var coords = GetCoordinates(ev);
		locatie_div.innerHTML = "(" + coords[0] + "," + coords[1] + ")";
		filterLocatie(coords[0], coords[1], straal_input.value);
	};
	
	straal_input.onkeyup = straal_input.onchange = function() {
		filterLocatie(coords[0], coords[1], straal_input.value);
	};
	
	function filterTag(tagid) {
		// TODO: this
	}
	
	function filterLocatie(x, y, straal) {
		// TODO: this
	}
	
	function showDiv(id, parent) {
		document.getElementById(id).style.display = "block";
		parent.onclick = function() {
			hideDiv(id, parent);
		};
	}
	
	function hideDiv(id, parent) {
		document.getElementById(id).style.display = "none";
		parent.onclick = function() {
			showDiv(id, parent);
		};
	}
	
</script>
<?php 
	require("footer.php");

