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
<div id="profielen">
	<div id="profielen-filter">
		<h3>Filter</h3>
		<p><b>Naam</b></p>
		<input id="naam" class="max">
		<p><b>Leeftijd</b></p>
		Van <input value="0" size="2" type="number" min="0" max="99" class="override-width">
		tot <input value="99" size="2" type="number" min="0" max="99" class="override-width"> jaar.
		<p><b>Tags</b></p>
		<ul class="no-listing"><?php 
			while ($tag = $tags->fetch_row()) {
				echo "<li><a href='javascript: filterTag(" . $tag[1] . ");'>" . $tag[0] . " (" . $tag[2] . ")</a></li>";
			}
		?></ul>
		<p><b>Locatie</b></p>
		Binnen <input value="40" size="2" type="number" min="0" max="1000" id="straal" class="override-width"> kilometer.
		<p><img src="res/images/benelux.png" id="kaart" /></p>
		<div id="locatie-output"></div>
	</div>
	<div id="profielen-result">
		<h3>Resultaten</h3>
		<p>Klik op een resultaat om meer info te zien!</p>
		<?php 
			while ($row = $users->fetch_array()) {
				// Voor nu weet ik geen betere oplossing, maar het fixt de [Todo] tenminste.
				$user_tags = $db->query("SELECT t.naam, t.id, COUNT(ut.id) FROM tags t INNER JOIN users_tags ut ON ut.tagid = t.id AND ut.userid = " . $row["userid"] . " GROUP BY ut.tagid HAVING COUNT(ut.id) > 0 ORDER BY t.naam")
					or die("Database error 69421337");
				
				$naam = htmlspecialchars(empty($row["naam"]) ? $row["gebruikersnaam"] : $row["naam"]);
				if ($row["geboortedatum"] == -1)
					$leeftijd = "";
				else
					$leeftijd = ", " . floor((time() - $row["geboortedatum"]) / (3600 * 24 * 365.25));
				
				echo "<div class=\"user\" onclick='showDiv(\"user" . $row["userid"] . "\", this);'>" .
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
	</div>
</div>
<div class="clear"></div>
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